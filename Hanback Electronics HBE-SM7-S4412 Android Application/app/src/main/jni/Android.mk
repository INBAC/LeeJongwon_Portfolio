LOCAL_PATH := $(call my-dir)

include $(CLEAR_VARS)

LOCAL_MODULE := JNIString
LOCAL_SRC_FILES := segment.cpp textlcd.cpp dotmatrix.cpp dipswitch.cpp piezo.cpp led.cpp
LOCAL_LDLIBS := -llog


include $(BUILD_SHARED_LIBRARY)