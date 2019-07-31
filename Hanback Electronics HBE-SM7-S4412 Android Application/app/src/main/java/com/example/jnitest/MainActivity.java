package com.example.jnitest;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;


public class MainActivity extends AppCompatActivity {

    Button redButton;
    static Thread myThread;
    static Thread myThread2;
    static Thread myThread3;
    public native int segment(int time);
    public final int[] ledData = {0xFF, 0X7F, 0x3F, 0x1F, 0x0f, 0x07, 0x03, 0x01, 0x00};
    private final static int timeLimit = 200;
    public final static int wrongAnswerPenalty = timeLimit/8;
    test t;
    static boolean returnFlag;
    public static int time;
    static {
        System.loadLibrary("JNIString");
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
       t = new test();
       returnFlag = false;
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        t.textlcd("Start page");

        redButton = (Button)findViewById(R.id.redButton);
        redButton.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view){
                time = timeLimit;
                myThread = new Thread(new Runnable() {
                    public void run() {
                        while(true) {
                            try {
                                if (time <= 0) {
                                    //Log.d("thread1", "time over!");
                                    t.led(0);
                                    Intent intent = new Intent(MainActivity.this, timeover.class);
                                    startActivity(intent);
                                    break;
                                } else {
                                }
                                Thread.sleep(1000);
                                if(time > 0)
                                    time--;
                                int numberOfLed = 0;
                                numberOfLed = (time+wrongAnswerPenalty-1)/wrongAnswerPenalty;
                                t.led(ledData[numberOfLed]);
                                //Log.d("thread1", String.format("%d sec", time));
                            } catch (InterruptedException e) {
                                //Log.d("test","thread1 killed");
                                break;
                            }
                        }
                    }
                });
                myThread3 = new Thread(new Runnable() {
                    public void run() {
                        while(true) {
                            //time = JNIString();
                            try {
                                if(time > 0)
                                    t.dotmatrix("Warning!!!");
                                else {
                                    t.dotmatrix("TIME OVER!!!");
                                    break;
                                }
                                myThread3.sleep(0);
                            } catch(InterruptedException e){
                                    t.dotmatrix("");
                                Log.d("test","thread3 killed");
                                    break;
                            }
                        }
                    }
                });

                myThread2 = new Thread(new Runnable() {
                    public void run() {

                        while(true) {
                            try {
                                segment(time);
                                if (time <= 0) {
                                    //Log.d("thread2", "time over!");
                                    //break;
                                } else {
                                }
                                //Log.d("thread2", String.format("%d sec", time));
                                myThread2.sleep(0);
                            } catch (InterruptedException e) {
                                Log.d("test","thread2 killed");
                                break;
                            }
                        }
                    }
                });

                //Log.d("click", myThread.isAlive()+"");
                myThread.start();
                myThread2.start();
                myThread3.start();

                Intent intent=new Intent(MainActivity.this,stage1.class);
                startActivity(intent);
            }
        });
    }

    @Override
    protected void onRestart() {

        super.onRestart();
        Log.d("restart","restart");
    }
    @Override
    protected void onPause() {

        super.onPause();
        Log.d("pause","pause");
    }
    @Override
    protected void onStop() {

        super.onStop();
        Log.d("stop","stop");
    }

    @Override
    protected void onResume(){
        super.onResume();
        Log.d("resume","resume");
        if(returnFlag == false) return;
        t = new test();
        returnFlag = false;
        t.textlcd("Start page");
        redButton = (Button)findViewById(R.id.redButton);
        redButton.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view){
                time = timeLimit;
                myThread = new Thread(new Runnable() {
                    public void run() {
                        while(true) {
                            //time = JNIString();
                            try {
                                if (time <= 0) {
                                    //Log.d("thread1", "time over!");
                                    t.led(0);
                                    Intent intent = new Intent(MainActivity.this, timeover.class);
                                    startActivity(intent);
                                    break;
                                } else {
                                }
                                Thread.sleep(1000);
                                if(time > 0)
                                    time--;
                                int numberOfLed = 0;
                                numberOfLed = (time+wrongAnswerPenalty-1)/wrongAnswerPenalty;
                                t.led(ledData[numberOfLed]);

                                //Log.d("thread1", String.format("%d sec", time));
                            } catch (InterruptedException e) {

                                break;
                            }
                        }
                    }
                });
                myThread3 = new Thread(new Runnable() {
                    public void run() {
                        while(true) {
                            //time = JNIString();
                            try {
                                if(time > 0)
                                    t.dotmatrix("Warning!!!");
                                else {
                                    t.dotmatrix("TIME OVER!!!");
                                    break;
                                }
                                myThread3.sleep(0);
                            } catch(InterruptedException e){
                                t.dotmatrix("");
                                break;
                            }
                        }
                    }
                });

                myThread2 = new Thread(new Runnable() {
                    public void run() {

                        while(true) {
                            try {
                                segment(time);
                                if (time == 0) {
                                    //Log.d("thread2", "time over!");
                                } else {
                                }
                                //Log.d("thread2", String.format("%d sec", time));
                            } catch (Throwable t) {

                            }
                        }
                    }
                });

                Log.d("click", myThread.isAlive()+"");
                if(myThread.isAlive() == false)
                    myThread.start();
                if(myThread2.isAlive() == false)
                    myThread2.start();
                if(myThread3.isAlive() == false)
                    myThread3.start();
                Intent intent=new Intent(MainActivity.this,stage1.class);
                startActivity(intent);
            }
        });
    }
    @Override
    public void onBackPressed(){
        t.textlcd("");
        t.led(0);
        finish();
    }
}
