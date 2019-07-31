package com.example.jnitest;

import android.content.Intent;
import android.graphics.Color;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.KeyEvent;
import android.widget.TextView;
import android.widget.Toast;

import java.util.Random;

public class stage3 extends AppCompatActivity {

    Random r = new Random();
    short field = 0;
    TextView[] textView;
    test t;
    final static int[] toggle = {0xC800, 0xE400, 0x7200, 0x3100, 0x8C80, 0x4E40, 0x2720, 0x1310, 0x08C8, 0x04E4, 0x0272, 0x0131, 0x008C, 0x004E, 0x0027, 0x0013};
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_stage3);
        t = new test();
        t.textlcd("stage 4");
        textView = new TextView[16];
        for(int i = 0; i < 16; i++){
            field ^= toggle[i] * r.nextInt(2);
        }

        textView[0] = (TextView)findViewById(R.id.textView1);
        textView[1] = (TextView)findViewById(R.id.textView2);
        textView[2] = (TextView)findViewById(R.id.textView3);
        textView[3] = (TextView)findViewById(R.id.textView4);
        textView[4] = (TextView)findViewById(R.id.textView5);
        textView[5] = (TextView)findViewById(R.id.textView6);
        textView[6] = (TextView)findViewById(R.id.textView7);
        textView[7] = (TextView)findViewById(R.id.textView8);
        textView[8] = (TextView)findViewById(R.id.textView9);
        textView[9] = (TextView)findViewById(R.id.textView10);
        textView[10] = (TextView)findViewById(R.id.textView11);
        textView[11] = (TextView)findViewById(R.id.textView12);
        textView[12] = (TextView)findViewById(R.id.textView13);
        textView[13] = (TextView)findViewById(R.id.textView14);
        textView[14] = (TextView)findViewById(R.id.textView15);
        textView[15] = (TextView)findViewById(R.id.textView16);
        printField();
    }

    public void printField(){
        int color;
        for(int i = 0; i < 16; i++){
            if(((field >>> (15-i)) & 0x1) == 1) color = Color.BLACK;
            else color = Color.WHITE;//0xff393c39;
            textView[i].setBackgroundColor(color);
        }
        if(field == 0 || field == -1){
            Toast toast = Toast.makeText(this, "clear", Toast.LENGTH_SHORT);
            toast.show();
            Intent intent = new Intent(stage3.this, stage4.class);
            startActivity(intent);
        }
    }

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        switch (keyCode) {
            case KeyEvent.KEYCODE_Q:
                field ^= toggle[0];
                break;
            case KeyEvent.KEYCODE_W:
                field ^= toggle[1];
                break;
            case KeyEvent.KEYCODE_E:
                field ^= toggle[2];
                break;
            case KeyEvent.KEYCODE_R:
                field ^= toggle[3];
                break;
            case KeyEvent.KEYCODE_T:
                field ^= toggle[4];
                break;
            case KeyEvent.KEYCODE_Y:
                field ^= toggle[5];
                break;
            case KeyEvent.KEYCODE_U:
                field ^= toggle[6];
                break;
            case KeyEvent.KEYCODE_I:
                field ^= toggle[7];
                break;
            case KeyEvent.KEYCODE_O:
                field ^= toggle[8];
                break;
            case KeyEvent.KEYCODE_P:
                field ^= toggle[9];
                break;
            case KeyEvent.KEYCODE_1:
                field ^= toggle[10];
                break;
            case KeyEvent.KEYCODE_2:
                field ^= toggle[11];
                break;
            case KeyEvent.KEYCODE_3:
                field ^= toggle[12];
                break;
            case KeyEvent.KEYCODE_4:
                field ^= toggle[13];
                break;
            case KeyEvent.KEYCODE_5:
                field ^= toggle[14];
                break;
            case KeyEvent.KEYCODE_6:
                field ^= toggle[15];
                break;
            default:
                return super.onKeyDown(keyCode, event);
        }
        printField();
        return true;
    }
    @Override
    public void onBackPressed(){

    }
}
