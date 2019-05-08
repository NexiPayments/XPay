package it.nexi.xpaysdksample.ui.main.result;

import it.nexi.xpaysdksample.ui.main.IBaseContract;
import it.nexi.xpaysdksample.ui.main.IMainContract;

public interface IResultContract {
    interface View extends IBaseContract.View {
        void displayTotalPrice(int totalPrice);
        void displayReportDialog(String reports);
    }

    interface Presenter {
        void onAttached();
        void goToFragment(Class fragmentClass);
        void showTotalPrice(int totalPrice);
        void ordersReport();
    }
}