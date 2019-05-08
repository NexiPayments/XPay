package it.nexi.xpaysdksample.data.entity;

import java.io.Serializable;

public class Settings implements Serializable {

    private String alias;
    private String key;
    private int env;
    private String terminalId;
    private String merchantName;

    public Settings(String alias, String key, int env, String terminalId, String merchantName) {
        this.alias = alias;
        this.key = key;
        this.env = env;
        this.merchantName = merchantName;
        this.terminalId = terminalId;
    }

    public String getAlias() {
        return alias;
    }

    public String getKey() {
        return key;
    }

    public int getEnv() {
        return env;
    }

    public String getMerchantName() {
        return merchantName;
    }

    public String getTerminalId() {
        return terminalId;
    }
}
