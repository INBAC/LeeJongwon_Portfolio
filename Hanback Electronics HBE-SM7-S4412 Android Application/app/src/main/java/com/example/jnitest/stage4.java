package com.example.jnitest;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.KeyEvent;
import android.widget.ImageView;


import java.util.Random;

public class stage4 extends AppCompatActivity {

    Random r = new Random();
    short field = 0;
    ImageView[] imageView;
    test t;
    final static int[] toggle = {0xC800, 0xE400, 0x7200, 0x3100, 0x8C80, 0x4E40, 0x2720, 0x1310,
                                    0x08C8, 0x04E4, 0x0272, 0x0131, 0x008C, 0x004E, 0x0027, 0x0013};

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_stage4);
        t = new test();
        t.textlcd("stage 3");
        imageView = new ImageView[16];

        imageView[0] = (ImageView)findViewById(R.id.imageView1);
        imageView[1] = (ImageView)findViewById(R.id.imageView2);
        imageView[2] = (ImageView)findViewById(R.id.imageView3);
        imageView[3] = (ImageView)findViewById(R.id.imageView4);
        imageView[4] = (ImageView)findViewById(R.id.imageView5);
        imageView[5] = (ImageView)findViewById(R.id.imageView6);
        imageView[6] = (ImageView)findViewById(R.id.imageView7);
        imageView[7] = (ImageView)findViewById(R.id.imageView8);
        imageView[8] = (ImageView)findViewById(R.id.imageView9);
        imageView[9] = (ImageView)findViewById(R.id.imageView10);
        imageView[10] = (ImageView)findViewById(R.id.imageView11);
        imageView[11] = (ImageView)findViewById(R.id.imageView12);
        imageView[12] = (ImageView)findViewById(R.id.imageView13);
        imageView[13] = (ImageView)findViewById(R.id.imageView14);
        imageView[14] = (ImageView)findViewById(R.id.imageView15);
        imageView[15] = (ImageView)findViewById(R.id.imageView16);

        for(int i = 0; i < 16; i++){
            field ^= toggle[i] * r.nextInt(2);
        }

        printField();
    }

    public void printField(){
        int image;
        for(int i = 0; i < 16; i++){
            if(((field >>> (15-i)) & 0x1) == 1) image = R.drawable.blueled; //color = Color.BLACK;
            else image = R.drawable.greenled;//color = Color.WHITE;//0xff393c39;
            imageView[i].setImageResource(image);
        }
        if(field == 0 || field == -1){
            Intent intent = new Intent(stage4.this, clear.class);
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
