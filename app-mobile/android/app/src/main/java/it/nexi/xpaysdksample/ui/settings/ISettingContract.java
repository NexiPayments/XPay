package it.nexi.xpaysdksample.ui.settings;

public interface ISettingContract {
    interface View{
        String getAliasString();
        String getSecretKeyString();
        String getEnvironment();
        void displaySnackBar(int idMsg);
        void setResult(Class activityClass);
        void displayDialog(int title, int text);
    }
    interface Presenter{
        void onDialogOkClicked(Class activityClass);
        void onApplyClick();
        }
}
