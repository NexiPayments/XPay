package it.nexi.xpaysdksample.ui.main;

import android.os.Bundle;

public interface IBaseContract {
    interface View {
        Bundle getBundle();
        IMainContract.Router getRouter();
        String getNoErrorAvailable();
    }
}
