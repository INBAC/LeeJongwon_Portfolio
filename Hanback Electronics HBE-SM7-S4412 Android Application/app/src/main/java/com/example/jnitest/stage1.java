package com.example.jnitest;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.TypedValue;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import java.util.Random;

public class stage1 extends AppCompatActivity {
    TextView byteQuiz;
    Button clear;
    int byteAnswer;
    public native int dipswitch();
    test t;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_stage1);
        t = new test();
        t.textlcd("stage 1");
        Random r = new Random();
        byteAnswer = r.nextInt() >>> 16;
        byteQuiz = (TextView)findViewById(R.id.byteQuiz);
        byteQuiz.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 22);
        byteQuiz.setText(String.format("0x%02X\t\t\t\t\t\t\t\t\t0x%02X", (byteAnswer >>> 8) & 0xFF, byteAnswer & 0xFF));

        clear = (Button)findViewById(R.id.clear1);
        clear.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view){
                if(dipswitch() == byteAnswer) {
                    Intent intent = new Intent(stage1.this, stage2.class);
                    startActivity(intent);
                }else{
                    if(MainActivity.time > MainActivity.wrongAnswerPenalty) {
                        MainActivity.time -= MainActivity.wrongAnswerPenalty;
                    }else{
                        MainActivity.time = 0;
                    }
                    if(MainActivity.time <= 0){
                        MainActivity.myThread2.interrupt();
                    }
                }
            }
        });

    }
    @Override
    public void onBackPressed(){

    }
}
