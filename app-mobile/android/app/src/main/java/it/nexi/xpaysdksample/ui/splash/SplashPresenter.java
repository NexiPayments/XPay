package it.nexi.xpaysdksample.ui.splash;

import android.os.Handler;
import it.nexi.xpaysdksample.utils.Constants;

public class SplashPresenter implements ISplashContract.Presenter {

    private ISplashContract.View mSpalshView;

    SplashPresenter(ISplashContract.View view){
        super();
        mSpalshView = view;
    }

    @Override
    public void setSplashScreen() {
        new Handler().postDelayed(new Runnable()
        {
            @Override
            public void run()
            {
                mSpalshView.openHomeActivity();
            }
        }, Constants.TIMEOUT);
    }
}
