package it.nexi.xpaysdksample.utils;


import it.nexi.xpay.Utils.EnvironmentUtils;

public class Constants {

    // Timeout for splash screen (2 sec)
    public static int TIMEOUT = 2000;

    // Key & alias for integration with payment
    public static String ALIAS = "";
    public static String SECRET_KEY = "";

    public static String TERMINAL_ID = "";

    // Google Pay constants
    public static String MERCHANT_NAME = "";

    // Environment
    public static EnvironmentUtils.Environment ENVIRONMENT = EnvironmentUtils.Environment.TEST;

    // SharedPrefs file
    public static String SHARED_PREFS_FILE = "XPaySDKSample";

}
