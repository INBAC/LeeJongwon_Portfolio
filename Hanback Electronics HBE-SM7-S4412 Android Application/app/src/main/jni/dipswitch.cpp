#include "com_example_jnitest_MainActivity.h"
#include <stdio.h>
#include <stdlib.h>
#include <assert.h>
#include<jni.h>
#include<string.h>
#include<fcntl.h>
#include<unistd.h>
#include <asm/unistd.h>
#include<termios.h>
#include<errno.h>
#include <termios.h>
#include <unistd.h>
#include<sys/mman.h>
#include <cstdlib>


JNIEXPORT jint JNICALL Java_com_example_jnitest_stage1_dipswitch (JNIEnv *env, jobject jobj) {
    int data=0;
    int temp;
    int temp2;
    int dipswInput[2];
    int fd = open("/dev/fpga_dipsw", O_RDWR);
    int ret = read(fd, &data, 2);
    if(ret == 2){
        dipswInput[0] = (data & 0xFF0000) >> 16;
        dipswInput[1] = (data & 0xFF);
        for(int i = 0; i < 2; i++){
            temp = dipswInput[i] & 0xf;
            temp2 = (temp >> 3) | ((temp & 0x1) << 3) | ((temp & 0x2) << 1)  | ((temp & 0x4) >> 1);

            temp = dipswInput[i] >> 4;
            temp2 |= ((temp >> 3) | ((temp & 0x1) << 3) | ((temp & 0x02) << 1) | ((temp & 0x4) >> 1)) << 4;
            dipswInput[i] = temp2;
        }
    }
    close(fd);
    data = dipswInput[0] | (dipswInput[1] << 8);
    return data;
}