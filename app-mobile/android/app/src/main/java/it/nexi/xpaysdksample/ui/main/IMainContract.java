package it.nexi.xpaysdksample.ui.main;

import android.content.Context;

import org.json.JSONObject;

import it.nexi.xpay.CallBacks.GooglePayCallback;
import it.nexi.xpay.GooglePay.GooglePayRequest;
import it.nexi.xpay.GooglePay.IGooglePayListener;
import it.nexi.xpay.Utils.EnvironmentUtils;
import it.nexi.xpaysdksample.data.entity.Settings;

public interface IMainContract{
    interface View extends IBaseContract.View{
        void displayNavigationDrawer();
        void startActivityOnResult(Class activityClass);
        void openFragment(Class fragmentClass, IBaseContract.View view);
        void payWithGoogle(Context context, GooglePayRequest request, GooglePayCallback handler);
        void checkGooglePayAvailability(EnvironmentUtils.Environment selectedEnvironment,
                                        JSONObject billingParameters,
                                        IGooglePayListener googlePayListener);
        <T> T getPref(String key);
        void saveSharedPreference(Settings settings);
        int getOkResultCode();
    }

    interface Presenter {
        void goToFragment(Class fragmentClass);
        void goToFragment(Class fragmentClass, IBaseContract.View view);
        void onSettingClicked(Class activityClass);
        void onCreateActivity();
        void onActivityResult(Settings settings, int requestCode, int resultCode);
        void onSharedPreferenceChanged(String key);
    }

    interface Router {
        void openFragment(Class fragmentClass, IBaseContract.View view);
    }
}
