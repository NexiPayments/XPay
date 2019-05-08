package it.nexi.xpaysdksample.ui.main.confirm;

import it.nexi.xpaysdksample.ui.main.IBaseContract;
import it.nexi.xpaysdksample.ui.main.IMainContract;

public interface IConfirmContract {
    interface View extends IBaseContract.View{
        void displayTotalPrice(int totalPrice);
    }

    interface Presenter {
        void goToFragment(Class fragmentClass);
        void showTotalPrice(int totalPrice);
        void onAttached();
    }
}
