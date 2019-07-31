#include<stdio.h>
#include<stdlib.h>
#include<string.h>
#include<unistd.h>
#include<sys/wait.h>
#include<sys/stat.h>
#include<sys/types.h>
#include<errno.h>
#include<time.h>

size_t buffersize = 256;
char *input;
char *para[64];
int slashFlag;
int statFlag;

void initialize();
void reset();
void printInfo();
void getInput();
void tokenInput();
void checkInput();
void execute();

void main()
{
	initialize();
	while(1)
	{
		reset();
		printInfo();
		getInput();
		tokenInput();
		checkInput();
		execute();
	}
}

void initialize()
{
	int i;
	for(i = 0; i < 64; i++)
	{
		para[i] = (char *)malloc(buffersize * sizeof(char));
	}
	return;
}

void reset()
{
	slashFlag = 2;
	statFlag = 2;
	return;
}

void printInfo()
{
	time_t result = time(NULL);
	printf("User: %s\nPWD: %s\nTime/Date: %s>", getenv("USER"), getenv("PWD"), ctime(&result));
	return;
}

void getInput()
{
	ssize_t length;
	length = getline(&input, &buffersize, stdin);
	*(input + (length - 1)) = '\0';
	if(strcmp(input, "quit") == 0)
	{
		exit(0);
	}
	else if(*input == '/')
	{
		slashFlag = 1;
	}
	else
	{
		slashFlag = 0;
	}
	return;
}

void tokenInput()
{
	char *temp[64];
	char string[256];
	int i;
	int j;
	strcpy(string, input);
	temp[0] = strtok(string, " ");
	for(i = 1; temp[i] = strtok(NULL, " "); i++);
	for(j = 0; j < i; j++)
	{
		strcpy(para[j], temp[j]);
	}
	para[j] = NULL;
	return;
}

void checkInput()
{
	struct stat fileStat;
	char *temp[64];
	char *str;
	char env[256];
	int i;
	int j;
	str = (char *)malloc(buffersize * sizeof(char));
	for(i = 0; i < 64; i++)
	{
		temp[i] = (char *)malloc(buffersize * sizeof(char));
	}
	if(slashFlag == 1)
	{
		statFlag = stat(para[0], &fileStat);
	}
	else if(slashFlag == 0)
	{
		strcpy(env, getenv("PATH"));
		temp[0] = strtok(env, ":");
		for(i = 1; temp[i] = strtok(NULL, ":"); i++);
		for(j = 0; j < i; j++)
		{
			strcpy(str, temp[j]);
			if(*(str + strlen(str) - 1) != '/')
			{
				strcat(str, "/\0");
			}
			strcat(str, para[0]);
			statFlag = stat(str, &fileStat);
			if(statFlag == 0)
			{
				strcpy(para[0], str);
				break;
			}
		}
	}
	return;
}

void execute()
{
	pid_t cpid;
	if(statFlag == 0)
	{
		cpid = fork();
		if(cpid == 0)
		{
			execve(para[0], para, NULL);
		}
		else if(cpid > 0)
		{
			wait(0);
		}
		else if(cpid < 0)
		{
			printf("Error forking\n");
		}
	}
	else if(statFlag == -1)
	{
		printf("No such executable\n");
	}
	return;
}
