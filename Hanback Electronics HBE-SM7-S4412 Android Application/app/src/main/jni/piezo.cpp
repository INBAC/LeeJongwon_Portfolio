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


JNIEXPORT void JNICALL Java_com_example_jnitest_stage2_piezo (JNIEnv *env, jobject jobj, jint data) {
    int fd, ret;

    fd = open("/dev/fpga_piezo", O_WRONLY);

    if (fd < 0) return;
    ret = write(fd, &data, 1);
    usleep(250000);
    close(fd);
    if (ret == 1) return;
    return;
}