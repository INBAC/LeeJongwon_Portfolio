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


#define TEXTLCD_H_

#define TEXTLCD_ON              1
#define TEXTLCD_OFF     2
#define TEXTLCD_INIT    3
#define TEXTLCD_CLEAR           4

#define TEXTLCD_LINE1           5
#define TEXTLCD_LINE2           6

/*
JNIEXPORT jint JNICALL Java_com_example_jnitest_test_textlcd (JNIEnv *env, jobject jobj, jint stage) {
    int fd;

    fd = open("/dev/fpga_textlcd", O_WRONLY);
    assert(fd != -1);

    ioctl(fd, TEXTLCD_INIT);

        char msgs[2][20] = {"stage", "test2"};
        msgs[0][5] = (char)(stage+48);
        msgs[0][6] = '\0';
        ioctl(fd, TEXTLCD_CLEAR);
        ioctl(fd, TEXTLCD_LINE1);
        write(fd, msgs[0], strlen(msgs[0]));



    ioctl(fd, TEXTLCD_OFF);
    close(fd);
    return 0;

}*/


JNIEXPORT jint JNICALL Java_com_example_jnitest_test_textlcd (JNIEnv *env, jobject jobj, jstring input) {
    int fd;

    fd = open("/dev/fpga_textlcd", O_WRONLY);
    assert(fd != -1);

    jboolean isSucceed;
    const char *string = (env)->GetStringUTFChars(input, &isSucceed);
    ioctl(fd, TEXTLCD_INIT);
    ioctl(fd, TEXTLCD_CLEAR);
    ioctl(fd, TEXTLCD_LINE1);
    write(fd, string, strlen(string));
    ioctl(fd, TEXTLCD_OFF);
    close(fd);
    return 0;

}
