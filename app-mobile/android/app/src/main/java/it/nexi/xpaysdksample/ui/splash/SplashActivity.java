package it.nexi.xpaysdksample.ui.splash;

import android.content.Intent;
import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;

import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.ui.main.MainActivity;

public class SplashActivity extends AppCompatActivity implements ISplashContract.View
{
    private ISplashContract.Presenter mSplashPresenter = new SplashPresenter(this);

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);
        mSplashPresenter.setSplashScreen();
    }

    @Override
    public void openHomeActivity() {
        Intent intent = new Intent(this, MainActivity.class);
        startActivity(intent);
        finish();
    }
}
