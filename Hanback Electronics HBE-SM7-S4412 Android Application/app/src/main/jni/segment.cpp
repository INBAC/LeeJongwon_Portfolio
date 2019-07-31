//
// Created by DKU on 2019-05-29.
//


#include "com_example_jnitest_MainActivity.h"
#include <stdio.h>
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


JNIEXPORT jint JNICALL Java_com_example_jnitest_MainActivity_segment (JNIEnv *env, jobject jobj, jint time) {

    int dev, ret;
    dev = open("/dev/fpga_segment", O_RDWR | O_SYNC);
    if(time > 0){
        if (dev != -1) {
            ret = write(dev, &time, sizeof(time));
        } else {
            exit(1);
        }
    }
    close(dev);
    return time;
}
