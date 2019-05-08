package it.nexi.xpaysdksample.ui.main.home;

import org.json.JSONObject;

import it.nexi.xpay.CallBacks.GooglePayCallback;
import it.nexi.xpay.GooglePay.GooglePayRequest;
import it.nexi.xpay.GooglePay.IGooglePayListener;
import it.nexi.xpay.Utils.EnvironmentUtils;
import it.nexi.xpaysdksample.data.network.response.ProductResponse;
import it.nexi.xpaysdksample.ui.main.IBaseContract;

public interface IHomeContract {
    interface View extends IBaseContract.View{
        void displayTotalPrice(int totalPrice);
        void displayRecyclerView(ProductResponse productResponse);
        void displayDialog(int title, int text);
        void displayDialog(int title, int text, String message);
        void displayChoiceDialog(int title, int text);
        void displayGooglePay(boolean enabled);
        void startGooglePay(GooglePayRequest googlePayRequest, GooglePayCallback googlePayCallback);
        void checkGooglePay(EnvironmentUtils.Environment selectedEnvironment,
                            JSONObject billingParameters,
                            IGooglePayListener googlePayListener);
        void setBundle(String transactionCode, int amount);
    }

    interface Presenter {
        void onHomeCreated();
        void onAttached();
        void goToNonceActivity();
        void onGooglePayClick();
        void onPayClick();
        void onPayChromeTabClick();
        void onPayWebViewClick();

    }
}