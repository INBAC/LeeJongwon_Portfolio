//
// Created by DKU on 2019-06-20.
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

JNIEXPORT void JNICALL Java_com_example_jnitest_test_led(JNIEnv *env, jobject jobj, jint data) {
    int fd;
    int ret;
    fd = open("/dev/fpga_led", O_RDWR);
    if(fd < 0) return;
    if(fd > 0) {
        data &= 0xff;
        write(fd, &data, 1);
        close(fd);
    }
}