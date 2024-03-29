#include <pthread.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>

#define MAX_BUFFER_SIZE 100
#define MAX_STRING_LENGTH 30
#define ASCII_SIZE	256

typedef struct{
	FILE *rfile;
	int linenum;
	int head;
	int tail;
	int proddone;
	char *line[MAX_BUFFER_SIZE];
	int full[MAX_BUFFER_SIZE];
	pthread_mutex_t lock[MAX_BUFFER_SIZE];
	pthread_mutex_t taillock;
	pthread_mutex_t headlock;
	int stat [MAX_STRING_LENGTH];
	int stat2 [ASCII_SIZE];
} so_t;

void char_stat(char *line, void *arg){
	so_t *so = arg;
	char *cptr = NULL;
	char *substr = NULL;
	char *brka = NULL;
	char *sep = "{}()[],;\" \n\t^";
	size_t length = 0;
	int i = 0;
	cptr = line;
	length = strlen(cptr);
	for (int i = 0 ; i < length ; i++) {
		if (*cptr < 256 && *cptr > 1) {
			so->stat2[*cptr]++;
		}
		cptr++;
	}
	cptr = line;
	for (substr = strtok_r(cptr, sep, &brka); substr; substr = strtok_r(NULL, sep, &brka))
	{
		length = strlen(substr);
		cptr = cptr + length + 1;
		if (length >= 30)
			length = 30;
		so->stat[length-1]++;
		if (*cptr == '\0')
			break;
	}
}

void *producer(void *arg) {
	so_t *so = arg;
	FILE *rfile = so->rfile;
	int *ret = malloc(sizeof(int));
	int i = 0;
	int temphead;
	char *line = NULL;
	size_t len = 0;
	ssize_t read = 0;

	while (1) {
		pthread_mutex_lock(&so->headlock);
		temphead = so->head;
		so->head++;
		if(so->head >= MAX_BUFFER_SIZE)
			so->head = 0;
		pthread_mutex_unlock(&so->headlock);

		pthread_mutex_lock(&so->lock[temphead]);
		if(so->full[temphead] == 1){
			pthread_mutex_unlock(&so->lock[temphead]);
		}
		else{
			read = getdelim(&line, &len, '\n', rfile);
			if (read == -1) {
				so->proddone = 1;
				so->full[temphead] = 1;
				so->line[temphead] = NULL;
				pthread_mutex_unlock(&so->lock[temphead]);
				break;
			}
			so->linenum = i;
			so->line[temphead] = strdup(line);
			so->full[temphead] = 1;
			i++;
			pthread_mutex_unlock(&so->lock[temphead]);
		}
	}
	free(line);
	printf("Prod_%x: %d lines\n", (unsigned int)pthread_self(), i);
	*ret = i;
	pthread_exit(ret);
}

void *consumer(void *arg) {
	so_t *so = arg;
	int *ret = malloc(sizeof(int));
	int i = 0;
	int temptail;
	char *line = NULL;
	int len = 0;

	while (1) {
		pthread_mutex_lock(&so->taillock);
		temptail = so->tail;
		so->tail++;
		if(so->tail >= MAX_BUFFER_SIZE)
			so->tail = 0;
		pthread_mutex_unlock(&so->taillock);

		pthread_mutex_lock(&so->lock[temptail]);
		if(so->full[temptail] == 0 && so->proddone == 0){
			pthread_mutex_unlock(&so->lock[temptail]);
		}
		else{
			line = so->line[temptail];
			if(so->proddone == 1 && line == NULL){
				pthread_mutex_unlock(&so->lock[temptail]);
				break;
			}
			printf("Cons_%x: [%02d:%02d] %s", (unsigned int)pthread_self(), i, so->linenum, line);
			char_stat(line, arg);
			so->line[temptail] = NULL;
			so->full[temptail] = 0;
			i++;
			pthread_mutex_unlock(&so->lock[temptail]);
		}
	}
	printf("Cons: %d lines\n", i);
	*ret = i;
	pthread_exit(ret);
}


int main (int argc, char *argv[])
{
	pthread_t prod[100];
	pthread_t cons[100];
	int Nprod, Ncons;
	int rc;   long t;
	int *ret;
	int i;
	int j;
	int sum;
	FILE *rfile;
	if (argc == 1) {
		printf("usage: ./prod_cons <readfile> #Producer #Consumer\n");
		exit (0);
	}
	so_t *share = malloc(sizeof(so_t));
	memset(share, 0, sizeof(so_t));
	memset(share->stat, 0, sizeof(share->stat));
	memset(share->stat2, 0, sizeof(share->stat2));
	rfile = fopen((char *) argv[1], "r");
	if (rfile == NULL) {
		perror("rfile");
		exit(0);
	}
	if (argv[2] != NULL) {
		Nprod = atoi(argv[2]);
		if (Nprod > 100) Nprod = 100;
		if (Nprod == 0) Nprod = 1;
	} else Nprod = 1;
	if (argv[3] != NULL) {
		Ncons = atoi(argv[3]);
		if (Ncons > 100) Ncons = 100;
		if (Ncons == 0) Ncons = 1;
	} else Ncons = 1;
	share->rfile = rfile;
	share->head = 0;
	share->tail = 0;
	share->proddone = 0;
	for(i = 0; i < 10; i++)
		share->line[i] = NULL;

	for(i = 0; i <MAX_BUFFER_SIZE; i++)
		pthread_mutex_init(&share->lock[i], NULL);
	pthread_mutex_init(&share->taillock, NULL);
	pthread_mutex_init(&share->headlock, NULL);

	for (i = 0 ; i < Nprod ; i++)
		pthread_create(&prod[i], NULL, producer, share);
	for (i = 0 ; i < Ncons ; i++)
		pthread_create(&cons[i], NULL, consumer, share);

	printf("main continuing\n");
	for (i = 0 ; i < Ncons ; i++) {
		rc = pthread_join(cons[i], (void **) &ret);
		printf("main: consumer_%d joined with %d\n", i, *ret);
	}
	for (i = 0 ; i < Nprod ; i++) {
		rc = pthread_join(prod[i], (void **) &ret);
		printf("main: producer_%d joined with %d\n", i, *ret);
	}

	sum = 0;
	for (i = 0 ; i < 30 ; i++)
		sum += share->stat[i];

	printf("*** print out distributions *** \n");
	printf("  #ch  freq \n");
	for (i = 0 ; i < 30 ; i++) {
		int num_star = share->stat[i]*80/sum;
		printf("[%3d]: %4d \t", i+1, share->stat[i]);
		for (j = 0 ; j < num_star ; j++)
			printf("*");
		printf("\n");
	}
	printf("       A        B        C        D        E        F        G        H        I        J        K        L        M        N        O        P        Q        R        S        T        U        V        W        X        Y        Z\n");
	printf("%8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d\n",
			share->stat2['A']+share->stat2['a'], share->stat2['B']+share->stat2['b'],  share->stat2['C']+share->stat2['c'],  share->stat2['D']+share->stat2['d'],  share->stat2['E']+share->stat2['e'],
			share->stat2['F']+share->stat2['f'], share->stat2['G']+share->stat2['g'],  share->stat2['H']+share->stat2['h'],  share->stat2['I']+share->stat2['i'],  share->stat2['J']+share->stat2['j'],
			share->stat2['K']+share->stat2['k'], share->stat2['L']+share->stat2['l'],  share->stat2['M']+share->stat2['m'],  share->stat2['N']+share->stat2['n'],  share->stat2['O']+share->stat2['o'],
			share->stat2['P']+share->stat2['p'], share->stat2['Q']+share->stat2['q'],  share->stat2['R']+share->stat2['r'],  share->stat2['S']+share->stat2['s'],  share->stat2['T']+share->stat2['t'],
			share->stat2['U']+share->stat2['u'], share->stat2['V']+share->stat2['v'],  share->stat2['W']+share->stat2['w'],  share->stat2['X']+share->stat2['x'],  share->stat2['Y']+share->stat2['y'],
			share->stat2['Z']+share->stat2['z']);

	pthread_exit(NULL);
	exit(0);
}

