package com.example.jnitest;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;

public class timeover extends AppCompatActivity {
    test t;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_timeover);
        t = new test();
        t.textlcd("END!!!");
    }


    public void retry(View v){
        MainActivity.returnFlag = true;
        if( MainActivity.myThread.isAlive())
            MainActivity.myThread.interrupt();
        if( MainActivity.myThread2.isAlive())
            MainActivity.myThread2.interrupt();
        if( MainActivity.myThread3.isAlive())
            MainActivity.myThread3.interrupt();
        Intent intent=new Intent(timeover.this,MainActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);

    }
    @Override
    public void onBackPressed(){
        if(MainActivity.myThread.isAlive()){
            MainActivity.myThread.interrupt();
        }
        if(MainActivity.myThread2.isAlive()){
            MainActivity.myThread2.interrupt();
        }
        if(MainActivity.myThread3.isAlive()){
            MainActivity.myThread3.interrupt();
        }
        t.textlcd("");
        t.led(0);
        finish();
    }
}
