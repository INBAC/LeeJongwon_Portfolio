package com.example.jnitest;

public class test {
    public native int textlcd(String input);
    //public native int textlcd(int input);
    public native void led(int data);
    public native int dotmatrix(String input);
    static {
        System.loadLibrary("JNIString");
    }
}
