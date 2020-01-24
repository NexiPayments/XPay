package it.nexi.xpaysdksample.ui.main.about;

import android.content.pm.PackageManager;
import android.os.Bundle;
import androidx.fragment.app.Fragment;

import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.Objects;

import androidx.fragment.app.Fragment;

import it.nexi.xpaysdksample.R;

public class AboutFrame extends Fragment implements IAboutContract.View {

    private static final String TAG = "About";

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        View rootView = inflater.inflate(R.layout.fragment_about, container, false);
        try {
            initViews(rootView);
        } catch (PackageManager.NameNotFoundException e) {
            Log.e(TAG, "Error: " + e.getLocalizedMessage());
        }
        return rootView;
    }

    private void initViews(View view) throws PackageManager.NameNotFoundException {
        // Retrieve app version from package manager
        String versionName = Objects.requireNonNull(getContext()).getPackageManager()
                .getPackageInfo(getContext().getPackageName(), 0).versionName;

        ((TextView) view.findViewById(R.id.text_number_version))
                .setText(it.nexi.xpay.BuildConfig.VERSION_NAME);
        ((TextView) view.findViewById(R.id.text_number_app_version))
                .setText(versionName);
    }
}
