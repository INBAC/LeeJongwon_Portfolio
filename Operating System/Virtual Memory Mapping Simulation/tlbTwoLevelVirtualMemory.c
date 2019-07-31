#include<stdio.h>
#include<stdlib.h>
#include<unistd.h>
#include<string.h>
#include<time.h>
#include<signal.h>
#include<sys/time.h>
#include<sys/types.h>
#include <sys/ipc.h>
#include <sys/msg.h>
#include "header.h"

#define PROCESS_NUM 10               // 유저프로세서의 개수
#define TICK_SEC 0                  // 초 단위 TICK 조정
#define TICK_USEC 1000               // 마이크로 초 단위 TICK 조정
#define OS_RUNNING_TIME 10000         // 총 RUNNING TIME 조정
#define TIME_QUANTUM 5               // 1QUANTUM 설정
#define BURST_RANGE 10               // CPU burst 값과 IO burst값을  random 하게 만들어줄때  % 해주는 변수
#define KEY_NUM 0x3655               // Msg Queue Key 값
#define PAGE_SIZE 0xFFF
#define CACHE_SIZE 64

#define PHYSICAL_MEMORY_SIZE 0x3FFFFFFF

typedef struct TLB_ENTRY{
   int ttbr;
   int virtual_page_number;
   int physical_page_number;
   int reference_bit;
}TLB_ENTRY;

typedef struct msgbuf{
   int mtype;
   int io_time;
   pid_t pid;
   int virtual_addr[10];
}msgbuf;

typedef struct PFNNODE{
   int PFN;
   struct PFNNODE *next;
}PFNNODE;

typedef struct PFNQUEUE{
   int count;
   PFNNODE *head;
   PFNNODE *tail;
}PFNQUEUE;

void userAction();                  // USER process가 작동하는 공간
void kernelHandler(int signo);         // Kernel handler
void userHandler(int signo);         // USER handler

PFNQUEUE *createPfnQueue();
int pfnDequeue(PFNQUEUE *queue);
void pfnEnqueue(PFNQUEUE *queue, int PFN);
void MMU (int virtualAddress, int ttbr);

struct QUEUE *readyQueue;            // ReadyQueue
struct QUEUE *waitQueue;            // WaitQueue
struct PCB *runQueue;               /* runQueue -> 현재는  1개의 프로세스를 runQueue에 저장하므로
                                 Queue의 형태가 아닌 process의 정보를 담고있는  pcb의 형태로 제작 되엇다. */

struct PCB *pcb[PROCESS_NUM];         // Kernel에서 USER process의 IO burst와 pid를 저장하는 곳

struct PFNQUEUE *kernelFreePage;
struct PFNQUEUE *userFreePage;
struct TLB_ENTRY tlb[CACHE_SIZE];

pid_t pid[PROCESS_NUM];
int osRunningTime = 0;               // 현재 Kernel의 실행 시간
int remainingCpuBurst;               // 남은 CPUburst값
pid_t kernelPid;                  // Kernel PID
int *physicalMemory;
int clock_hand_pointer = 0;

int main()
{
   int i;
   int runCount = 0;
   int ioCount = 0;
   kernelPid = getpid();
   int msgq;
   int key = KEY_NUM;
   int VA;

   struct msgbuf msg;
   struct PCB *waitTemp;

   physicalMemory = (int*)malloc(sizeof(int)*PHYSICAL_MEMORY_SIZE);
   memset(physicalMemory, 0, sizeof(int)*PHYSICAL_MEMORY_SIZE);
   memset(&tlb, 0 , sizeof(TLB_ENTRY) * CACHE_SIZE);

   kernelFreePage = createPfnQueue();
   userFreePage = createPfnQueue();

   readyQueue = createQueue();
   waitQueue = createQueue();

   struct sigaction oldKernel, newKernel;
   printf("Kernel with %d\n", getpid());
/*/////////////////////////////////////////////////////////////////////

                  PHYSICAL MEMORY SIZE SETTING

///////////////////////////////////////////////////////////////////////*/

    for(i = 0; i < PHYSICAL_MEMORY_SIZE; i += PAGE_SIZE)
    {
         if(i < (PHYSICAL_MEMORY_SIZE / 4))
            pfnEnqueue(kernelFreePage, i);
         else
            pfnEnqueue(userFreePage, i);
    }
    printf("Physical Memory Setting Done!\n");

   for (i = 0; i < PROCESS_NUM; i++)
   {
      pid[i] = fork();
      if (pid[i] == 0)
      {
         printf("User #%d with %d\n", i, getpid());
         srand((int)time(NULL) + i);
         userAction();
      }
      else if(pid[i] < 0)
      {
         printf("ERROR\n");
         exit(0);
      }
      pcb[i] = (PCB*)malloc(sizeof(PCB));
      memset(pcb[i], 0, sizeof(PCB));
      pcb[i]->pid = pid[i];
      pcb[i]->ttbr = pfnDequeue(kernelFreePage);
      pcb[i]->remainingIoBurst = 0;
      pcb[i]->ioBurst = 0;
      pcb[i]->timeQuantum = TIME_QUANTUM;
      enqueue(readyQueue, pcb[i]); //change
   }
   memset(&msg, 0, sizeof(msg));
   msgq = msgget( key, IPC_CREAT | 0666);
   memset(&newKernel, 0, sizeof(newKernel));
   newKernel.sa_handler = &kernelHandler;
   sigaction(SIGALRM, &newKernel, &oldKernel);
   runQueue = dequeue(readyQueue);
   runCount++;

   struct itimerval new_itimer, old_itimer;
   new_itimer.it_interval.tv_sec = TICK_SEC;
   new_itimer.it_interval.tv_usec = TICK_USEC;
   new_itimer.it_value.tv_sec = TICK_SEC;
   new_itimer.it_value.tv_usec = TICK_USEC;
   setitimer(ITIMER_REAL, &new_itimer, &old_itimer);

   while (osRunningTime <= OS_RUNNING_TIME)
   {
      if(msgrcv(msgq, &msg, sizeof(msg), 0, IPC_NOWAIT) != -1)
      {
         if(msg.mtype == 0){
         //   printf("%d I/O request message received\n", msg.pid);
            if(runQueue->pid == msg.pid)
            {
               runQueue->ioBurst = msg.io_time;
               runQueue->timeQuantum = TIME_QUANTUM;
               priorityEnqueue(waitQueue, runQueue);
               runQueue = dequeue(readyQueue);
               runCount++;
            }
         }
         else if (msg.mtype == 1){
            for(int i = 0; i < 10; i++){
               MMU(msg.virtual_addr[i], runQueue->ttbr);
            }
         }
      }
      else if(runQueue->timeQuantum == 0)
      {
         runQueue->timeQuantum = TIME_QUANTUM;
         enqueue(readyQueue, runQueue);
         runQueue = dequeue(readyQueue);
         runCount++;
      }

      while(waitQueue->count != 0 && waitQueue->head->pcb->remainingIoBurst == 0)
      {
         waitTemp = dequeue(waitQueue);
         enqueue(readyQueue, waitTemp);
         ioCount++;
      }
   }
   for(i = 0; i < PROCESS_NUM; i++)
      kill(pid[i], SIGKILL);
   free(physicalMemory);
   printf("OS Times Up! Ending Now\nTotal Run Queue Completion: %d\nTotal Wait Queue Completion: %d\n", runCount, ioCount);
   exit(0);
}

void userAction()
{
   int msgq;
   int ret;
   int key = KEY_NUM;
   struct msgbuf msg;
   msgq = msgget( key, IPC_CREAT | 0666);
   memset(&msg, 0, sizeof(msg));
   struct sigaction oldUser, newUser;

   int cpuBurst = (rand() % BURST_RANGE) + 1;
   int ioBurst = (rand() % BURST_RANGE) + 1;

   remainingCpuBurst = cpuBurst;
   memset(&newUser, 0, sizeof(newUser));
   newUser.sa_handler = &userHandler;
   sigaction(SIGUSR1, &newUser, &oldUser);

   while (1)
   {
      if(remainingCpuBurst <= 0)
      {
         msg.mtype = 0;
         msg.pid = getpid();
         msg.io_time = ioBurst;
         ret = msgsnd(msgq, &msg, sizeof(msg), 0);
         remainingCpuBurst = cpuBurst;
      }
   }
}

void kernelHandler(int signo)
{
   kill(runQueue->pid, SIGUSR1);
   osRunningTime++;
   runQueue->timeQuantum--;
   if(waitQueue->count != 0)
      waitQueue->head->pcb->remainingIoBurst--;

}

void userHandler(int signo)
{
    int msgq;
    int ret;
    int key = KEY_NUM;
    int rando;
     struct msgbuf msg;
    msgq = msgget( key, IPC_CREAT | 0666);
    memset(&msg, 0, sizeof(msg));

   remainingCpuBurst--;
   srand((int)time(NULL));
   for(int i = 0; i < 10; i++)
   {
      rando = rand() & 0xff;
      rando |= (rand() & 0xff) << 8;
      rando |= (rand() & 0xff) << 16;
      rando |= (rand() & 0xff) << 24;
      msg.virtual_addr[i] = rando;
   }
   msg.mtype = 1;                        // If, msg.mtype is 1 --> Process request about memory space
   ret = msgsnd(msgq, &msg, sizeof(msg), 0);
}

PFNQUEUE* createPfnQueue()
{
   PFNQUEUE *newQueue = (PFNQUEUE*)malloc(sizeof(PFNQUEUE));
   newQueue->count = 0;
   newQueue->head = NULL;
   newQueue->tail = NULL;
   return newQueue;
}

int pfnDequeue(PFNQUEUE *queue){
   int i;
   if(queue->count != 0)
   {
      PFNNODE *nodeTemp = queue->head;
      int PFN = queue->head->PFN;
      if(queue->count == 1)
      {
         queue->head = NULL;
         queue->tail = NULL;
         queue->count--;
      }
      else
      {
         queue->head = queue->head->next;
         queue->count--;
         nodeTemp->next = NULL;
      }
      free(nodeTemp);
      return PFN;
   }
   else{
      for(i = 0; i < PROCESS_NUM; i++)
            kill(pid[i], SIGKILL);
      free(physicalMemory);
      printf("NO MORE FREE PAGE\n");
      exit(0);
   }
}
void pfnEnqueue(PFNQUEUE *queue, int PFN)
{
   PFNNODE *newNode = (PFNNODE*)malloc(sizeof(PFNNODE));
   newNode->PFN = PFN;
   newNode->next = NULL;
   if(queue->count == 0)
   {
      queue->head = newNode;
      queue->tail = newNode;
      queue->count++;
   }
   else
   {
      queue->tail->next = newNode;
      queue->tail = queue->tail->next;
      queue->count++;
   }
}

void MMU (int virtualAddress, int ttbr){
   int i;
   int physicalAddress;
   int offset = virtualAddress & 0xFFF;
   int virtual_page_number = virtualAddress & 0xfffff000;

   for(int i = 0; i < CACHE_SIZE; i++){
      if(tlb[i].ttbr == ttbr && tlb[i].virtual_page_number == virtual_page_number){                   //   FOUND!!!!
         tlb[i].reference_bit = 1;
         physicalAddress = tlb[i].physical_page_number + offset;
         printf("TLB FOUND\n");
         printf("--- TICK #%d  PID  #%d --- VA : %08X -> PA : %08X\n",osRunningTime, runQueue->pid, virtualAddress ,physicalAddress);
         return;
      }
   }
   // NOT FOUND !!!
   int firstLevelIndex = ttbr + ((virtualAddress & 0xFFC00000) >> 22);
   int secondLevelIndex;

   if((physicalMemory[firstLevelIndex] & 1) == 0)
   {
     physicalMemory[firstLevelIndex] = pfnDequeue(kernelFreePage) + 1;
     secondLevelIndex = (physicalMemory[firstLevelIndex] & 0xFFFFF000) + ((virtualAddress & 0x003FF000) >> 12);
   }
   else if((physicalMemory[firstLevelIndex] & 1) == 1)
   {
     secondLevelIndex = (physicalMemory[firstLevelIndex] & 0xFFFFF000) + ((virtualAddress & 0x003FF000) >> 12);
   }

   if((physicalMemory[secondLevelIndex] & 1) == 0)
   {
     physicalMemory[secondLevelIndex] = pfnDequeue(userFreePage) + 1;
     physicalAddress = (physicalMemory[secondLevelIndex] & 0xFFFFF000) + offset;
   }
   else if((physicalMemory[secondLevelIndex] & 1) == 1)
     physicalAddress = (physicalMemory[secondLevelIndex] & 0xFFFFF000) + offset;

   physicalMemory[physicalAddress] = physicalAddress;

   ////////// SAVE TO TLB ///////////////////////////
   while(1){
      if(tlb[clock_hand_pointer].reference_bit == 0){
         tlb[clock_hand_pointer].ttbr = ttbr;
         tlb[clock_hand_pointer].virtual_page_number = virtual_page_number + 1;
         tlb[clock_hand_pointer].physical_page_number = physicalAddress & 0xfffff000;
         clock_hand_pointer = (clock_hand_pointer + 1) % CACHE_SIZE;
         break;
      }
      else if(tlb[clock_hand_pointer].reference_bit == 1){
         tlb[clock_hand_pointer].reference_bit = 0;
         clock_hand_pointer = (clock_hand_pointer + 1) % CACHE_SIZE;
      }
   }
   printf("--- TICK #%d  PID  #%d --- VA : %08X -> PA : %08X\n",osRunningTime, runQueue->pid, virtualAddress ,physicalAddress);
   return;
}
