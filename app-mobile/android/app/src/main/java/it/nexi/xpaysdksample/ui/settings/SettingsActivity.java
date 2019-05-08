package it.nexi.xpaysdksample.ui.settings;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.constraint.ConstraintLayout;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.Spinner;

import it.nexi.xpay.Utils.EnvironmentUtils;
import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.ui.main.MainActivity;
import it.nexi.xpaysdksample.utils.Constants;

public class SettingsActivity extends AppCompatActivity implements ISettingContract.View {

    private ConstraintLayout mView;
    private Spinner spinner;
    private EditText alias, secretKey, terminalId, merchantName;
    private ISettingContract.Presenter mSettingsPresenter;

    public static int REQUEST_CODE = 11;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_settings);

        mView = findViewById(R.id.root_layout);

        SharedPreferences sharedPref = getSharedPreferences(Constants.SHARED_PREFS_FILE, Context.MODE_PRIVATE);

        mSettingsPresenter = new SettingsPresenter(this);
        spinner = findViewById(R.id.spinner_environment);

        alias = findViewById(R.id.input_alias);
        secretKey = findViewById(R.id.input_secret_key);
        terminalId = findViewById(R.id.input_merchant_id);
        merchantName = findViewById(R.id.input_merchant_name);

        FloatingActionButton buttonSave = findViewById(R.id.button_save);
        buttonSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mSettingsPresenter.onApplyClick();
            }
        });

        spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {

            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });

        // Set fields with current setting values
        alias.setText(Constants.ALIAS);
        secretKey.setText(Constants.SECRET_KEY);
        terminalId.setText(Constants.TERMINAL_ID);
        merchantName.setText(Constants.MERCHANT_NAME);
        spinner.setSelection(Constants.ENVIRONMENT.ordinal());

    }

    @Override
    public String getAliasString() {
        return alias.getText().toString();
    }

    @Override
    public String getSecretKeyString() {
        return secretKey.getText().toString();
    }

    @Override
    public String getEnvironment() {
        return spinner.getSelectedItem().toString();
    }

    @Override
    public void setResult(Class activityClass) {
        Intent i = new Intent();

        i.putExtra("alias", alias.getText().toString());
        i.putExtra("key", secretKey.getText().toString());
        i.putExtra("env", spinner.getSelectedItemPosition());
        i.putExtra("terminalId", terminalId.getText().toString());
        i.putExtra("merchantName", merchantName.getText().toString());

        setResult(RESULT_OK, i);
        finish();
    }

    @Override
    public void displaySnackBar(int idMsg) {
        Snackbar.make(mView, getResources().getString(idMsg), Snackbar.LENGTH_LONG)
                .setAction(R.string.cancel, new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                    }
                })
                .setActionTextColor(getResources().getColor(android.R.color.holo_red_light))
                .show();
    }

    // Display dialog with custom message
    @Override
    public void displayDialog(int title, int text) {
        new AlertDialog.Builder(mView.getContext())
                .setTitle(title)
                .setMessage(getString(text))
                .setNeutralButton(R.string.cancel, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {

                    }
                })
                .setPositiveButton(android.R.string.ok, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                mSettingsPresenter.onDialogOkClicked(MainActivity.class);
                            }
                        }

                )
                .show();
    }
}
