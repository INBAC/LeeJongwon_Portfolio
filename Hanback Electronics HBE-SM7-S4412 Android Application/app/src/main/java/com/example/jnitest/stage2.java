package com.example.jnitest;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import java.util.Random;

public class stage2 extends AppCompatActivity {
    Random r = new Random();
    TextView piezoInput;
    Button[] piezoButton;
    Button clear;
    Button play;
    String piezoPrintString = "";
    String piezoQuestionString = "";
    Thread piezoThread;
    int[] piezoQuestion;
    test t;
    public static native void piezo(int data);
    final static String[] note = {"도", "레", "미", "파", "솔", "라", "시"};
    final static int[] piezoData = {0x01, 0x02, 0x03, 0x4, 0x5, 0x6, 0x7};
    final int backspace = 7;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_stage2);
        piezoQuestion = new int[5];
        for(int i = 0; i < 4; i++){
            int randomNote = r.nextInt(7) + 1;
            piezoQuestion[i] = randomNote;
            piezoQuestionString += note[randomNote-1];
        }
        piezoQuestion[4] = 0;

        t = new test();
        t.textlcd("stage 2");
        piezoButton = new Button[8];
        piezoInput = (TextView)findViewById(R.id.piezoInput);

        piezoButton[0] = (Button)findViewById(R.id.Do);
        piezoButton[1] = (Button)findViewById(R.id.re);
        piezoButton[2] = (Button)findViewById(R.id.mi);
        piezoButton[3] = (Button)findViewById(R.id.fa);
        piezoButton[4] = (Button)findViewById(R.id.so);
        piezoButton[5] = (Button)findViewById(R.id.la);
        piezoButton[6] = (Button)findViewById(R.id.ti);
        piezoButton[7] = (Button)findViewById(R.id.backspace);

        clear = (Button)findViewById(R.id.clear2);
        clear.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view){
                if(piezoPrintString.equals(piezoQuestionString)) {
                    if(piezoThread != null && piezoThread.isAlive())
                        piezoThread.interrupt();
                    Intent intent = new Intent(stage2.this, stage4.class);
                    startActivity(intent);
                }else{
                    if(MainActivity.time > MainActivity.wrongAnswerPenalty) {
                        MainActivity.time -= MainActivity.wrongAnswerPenalty;
                    }else{
                        MainActivity.time = 0;
                    }
                    if(MainActivity.time <= 0){
                        if(MainActivity.myThread2.isAlive())
                            MainActivity.myThread2.interrupt();
                    }
                }
            }
        });

        play = (Button)findViewById(R.id.play);
        play.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view){
                piezoThread = new Thread(new Runnable() {
                    public void run() {
                            try {
                                for(int i = 0; i < piezoQuestion.length; i++){
                                    piezo(piezoQuestion[i]);
                                }
                            } catch (Throwable t) {
                            }
                    }
                });
                if(piezoThread.isAlive() == false)
                    piezoThread.start();
            }
        });
    }

    public void piezoPrint(View v){
        int buttonNumber = 0;
        for(int i = 0; i < 8; i++)
            if(v == piezoButton[i]){
                buttonNumber = i;
            }
        if(buttonNumber != backspace){
            if( piezoPrintString.length() < 4)
                piezoPrintString += note[buttonNumber];
        }else{
            if(piezoPrintString.length() > 0){
                piezoPrintString = piezoPrintString.substring(0, piezoPrintString.length()-1);
            }
        }
        piezoInput.setText(piezoPrintString);
    }
    @Override
    public void onBackPressed(){

    }
}
