package it.nexi.xpaysdksample.ui.main.nonce;

import it.nexi.xpaysdksample.ui.main.IBaseContract;
import it.nexi.xpaysdksample.ui.main.IMainContract;

public interface INonceContract {
    interface View extends IBaseContract.View {
        void createNonce (int amount, String transCode);
        int getTotalPrice();
        String getTransCode();
        void displayDialog(int title, int text, String message);
        void addNonceToBundle(String nonce);
        void displaySnackBar(int idMsg);
    }

    interface Presenter {
        void onPayClick();
        void onSuccessCreateNonce(String nonce);
        void onErrorCreateNonce(String msg);
        void onAttached();
        void onInvalidPan();
        void onInvalidExpiry();
        void onInvalidCvv();
    }
}
