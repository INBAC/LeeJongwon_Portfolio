package com.example.jnitest;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;

public class clear extends AppCompatActivity {
    test t;
    Thread myThread3;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_clear);
        t = new test();
        t.textlcd("finish");

        if(MainActivity.myThread.isAlive()){
            MainActivity.myThread.interrupt();
            Log.d("test","(main)thread2 killed");
        }
        if(MainActivity.myThread3.isAlive()){
            MainActivity.myThread3.interrupt();
            Log.d("test","(main)thread3 killed");
        }

        myThread3 = new Thread(new Runnable() {
            public void run() {
                while(true) {
                    //time = JNIString();
                    try {
                        t.dotmatrix("SUCCESS!!!");
                        myThread3.sleep(0);
                    } catch(InterruptedException e){
                        t.dotmatrix("");
                        Log.d("test","(clear)thread3 killed");
                        break;
                    }
                }
            }
        });
        myThread3.start();

    }
    @Override
    public void onBackPressed(){
        if(myThread3.isAlive()){
            myThread3.interrupt();
        }
        if(MainActivity.myThread2.isAlive()){
            MainActivity.myThread2.interrupt();
        }
        t.textlcd("");
        t.led(0);
        finish();
    }
}
