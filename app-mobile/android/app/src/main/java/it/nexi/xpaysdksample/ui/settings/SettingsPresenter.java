package it.nexi.xpaysdksample.ui.settings;



import it.nexi.xpaysdksample.R;

public class SettingsPresenter implements ISettingContract.Presenter {

    private ISettingContract.View mSettingView;

    SettingsPresenter(ISettingContract.View view){
        super();
        mSettingView = view;
    }

    @Override
    public void onApplyClick() {
        String alias = mSettingView.getAliasString();
        String secretKey = mSettingView.getSecretKeyString();
        if(alias.isEmpty() || secretKey.isEmpty()){
            mSettingView.displaySnackBar(R.string.fill_fields);

        } else{
            mSettingView.displayDialog(R.string.are_you_sure, R.string.are_you_sure_text);
        }
    }

    @Override
    public void onDialogOkClicked(Class activity){
        mSettingView.setResult(activity);
    }
}
