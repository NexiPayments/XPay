package it.nexi.xpaysdksample.ui.main;


import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import com.google.android.material.navigation.NavigationView;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.widget.Toolbar;
import android.view.MenuItem;

import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.widget.Toolbar;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import com.google.android.material.navigation.NavigationView;

import it.nexi.xpay.GooglePay.GooglePayActivity;
import it.nexi.xpaysdksample.R;
import it.nexi.xpaysdksample.data.entity.Settings;
import it.nexi.xpaysdksample.ui.main.about.AboutFrame;
import it.nexi.xpaysdksample.ui.main.home.HomeFrame;
import it.nexi.xpaysdksample.ui.settings.SettingsActivity;
import it.nexi.xpaysdksample.utils.Constants;

public class MainActivity extends GooglePayActivity
        implements NavigationView.OnNavigationItemSelectedListener, IMainContract.View, IMainContract.Router {

    private DrawerLayout drawer;
    private IMainContract.Presenter mMainPresenter = new MainPresenter(this);
    private SharedPreferences sharedPref;

    public SharedPreferences.OnSharedPreferenceChangeListener mListener = new SharedPreferences.OnSharedPreferenceChangeListener() {
        @Override
        public void onSharedPreferenceChanged(SharedPreferences sharedPreferences, String key) {
            mMainPresenter.onSharedPreferenceChanged(key);
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);

        // Load Alias, Key and Env from app shared prefs
        sharedPref = this.getSharedPreferences(Constants.SHARED_PREFS_FILE, Context.MODE_PRIVATE);
        sharedPref.registerOnSharedPreferenceChangeListener(mListener);

        mMainPresenter.onCreateActivity();
    }

    public <T> T getPref(String key){
        return (T)sharedPref.getAll().get(key);
    }

    @Override
    public void saveSharedPreference(Settings settings) {
        SharedPreferences.Editor editor = getSharedPreferences(Constants.SHARED_PREFS_FILE, MODE_PRIVATE).edit();
        editor.putString("alias", settings.getAlias());
        editor.putString("key", settings.getKey());
        editor.putInt("env", settings.getEnv());
        editor.putString("terminalId", settings.getTerminalId());
        editor.putString("merchantName", settings.getMerchantName());
        editor.apply();
    }

    @Override
    public int getOkResultCode() {
        return RESULT_OK;
    }

    // Display navigation drawer
    @Override
    public void displayNavigationDrawer() {
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        drawer = findViewById(R.id.drawer_layout);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                this, drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close);
        drawer.addDrawerListener(toggle);
        toggle.syncState();
        NavigationView navigationView = findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);
    }

    @Override
    public void startActivityOnResult(Class activityClass) {
        Intent i = new Intent(this, activityClass);
        startActivityForResult(i, SettingsActivity.REQUEST_CODE);
    }

    @Override
    public void onBackPressed() {
        DrawerLayout drawer = findViewById(R.id.drawer_layout);
        if (drawer.isDrawerOpen(GravityCompat.START)) {
            drawer.closeDrawer(GravityCompat.START);
        } else {
            super.onBackPressed();
        }
    }

    // Manage item selection in the navigation drawer
    @Override
    public boolean onNavigationItemSelected(MenuItem item) {
        // Handle navigation view item clicks here.
        Class fragmentClass = null;
        int id = item.getItemId();

        if (id == R.id.nav_settings) {
            mMainPresenter.onSettingClicked(SettingsActivity.class);
            item.setChecked(false);
        }
        else{
            if (id == R.id.nav_home) {
                fragmentClass = HomeFrame.class;
                mMainPresenter.goToFragment(fragmentClass);
            } else if (id == R.id.nav_about) {
                fragmentClass = AboutFrame.class;
                mMainPresenter.goToFragment(fragmentClass);
            }
            // Highlight the selected item has been done by NavigationView
            item.setChecked(true);
            // Set action bar title
            setTitle(item.getTitle());
        }
        
        // Close the navigation drawer
        drawer.closeDrawers();

        DrawerLayout drawer = findViewById(R.id.drawer_layout);
        drawer.closeDrawer(GravityCompat.START);
        return true;
    }

    // Opens the fragment and passes the bundle (if any)
    @Override
    public void openFragment(Class fragmentClass, IBaseContract.View view) {
        Fragment fragment = null;
        try {
            fragment = (Fragment) fragmentClass.newInstance();
        } catch (InstantiationException | IllegalAccessException e) {
            e.printStackTrace();
        }
        if (view.getBundle() != null) {
            assert fragment != null;
            fragment.setArguments(view.getBundle());
        }

        FragmentManager fragmentManager = getSupportFragmentManager();
        fragmentManager
                .beginTransaction()
                .setCustomAnimations(R.anim.enter_from_right, R.anim.exit_to_left)
                .replace(R.id.main_frame, fragment)
                .commitAllowingStateLoss();
    }

    @Override
    public Bundle getBundle() {
        return null;
    }

    @Override
    public IMainContract.Router getRouter() {
        return null;
    }

    @Override
    public String getNoErrorAvailable() {
        return getResources().getString(R.string.error_not_found);
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (data != null) {
            Settings settings = new Settings(data.getStringExtra("alias"),
                    data.getStringExtra("key"),
                    data.getIntExtra("env", 0),
                    data.getStringExtra("terminalId"),
                    data.getStringExtra("merchantName"));
            mMainPresenter.onActivityResult(settings, requestCode, resultCode);
        }
    }
}
