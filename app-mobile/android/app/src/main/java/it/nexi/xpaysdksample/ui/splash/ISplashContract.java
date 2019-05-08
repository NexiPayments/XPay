package it.nexi.xpaysdksample.ui.splash;


public interface ISplashContract {
    interface View {
        void openHomeActivity();
    }

    interface Presenter {
        void setSplashScreen();
    }
}

