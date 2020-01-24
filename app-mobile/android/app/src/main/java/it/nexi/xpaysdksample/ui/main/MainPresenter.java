package it.nexi.xpaysdksample.ui.main;

import it.nexi.xpay.Utils.EnvironmentUtils;
import it.nexi.xpaysdksample.data.entity.Settings;
import it.nexi.xpaysdksample.ui.main.home.HomeFrame;
import it.nexi.xpaysdksample.ui.settings.SettingsActivity;
import it.nexi.xpaysdksample.utils.Constants;

public class MainPresenter implements IMainContract.Presenter {

    private IMainContract.View mMainView;

    MainPresenter(IMainContract.View view) {
        super();
        mMainView = view;
    }

    @Override
    public void goToFragment(Class fragmentClass) {
        goToFragment(fragmentClass, mMainView);
    }

    @Override
    public void goToFragment(Class fragmentClass, IBaseContract.View view) {
        mMainView.openFragment(fragmentClass, view);
    }

    @Override
    public void onSettingClicked(Class activityClass) {
        mMainView.startActivityOnResult(activityClass);
    }

    @Override
    public void onCreateActivity() {
        Constants.ALIAS = mMainView.getPref("alias") == null ? "" : mMainView.getPref("alias");
        Constants.SECRET_KEY = mMainView.getPref("key") == null ? "" : mMainView.getPref("key");
        Integer position = mMainView.<Integer>getPref("env") == null ? 0 : mMainView.<Integer>getPref("env");
        Constants.ENVIRONMENT = EnvironmentUtils.Environment.values()[position];
        Constants.TERMINAL_ID = mMainView.getPref("terminalId") == null ? "" : mMainView.getPref("terminalId");
        Constants.MERCHANT_NAME = mMainView.getPref("merchantName") == null ? "" : mMainView.getPref("merchantName");

        mMainView.displayNavigationDrawer();
        goToFragment(HomeFrame.class, mMainView);
    }


    @Override
    public void onActivityResult(Settings settings, int requestCode, int resultCode) {
        if (resultCode == mMainView.getOkResultCode() && requestCode == SettingsActivity.REQUEST_CODE) {
            mMainView.saveSharedPreference(settings);
        }
    }

    @Override
    public void onSharedPreferenceChanged(String key) {
        switch (key) {
            case "env":
                Constants.ENVIRONMENT = EnvironmentUtils.Environment.values()[mMainView.<Integer>getPref(key)];
                break;
            case "alias":
                Constants.ALIAS = mMainView.getPref(key);
                break;
            case "key":
                Constants.SECRET_KEY = mMainView.getPref(key);
                break;
            case "terminalId":
                Constants.TERMINAL_ID = mMainView.getPref(key);
                break;
            case "merchantName":
                Constants.MERCHANT_NAME = mMainView.getPref(key);
                break;
        }
    }
}
