package com.innovitrix.napsamarketsales;

import android.app.Application;

import io.realm.Realm;
import io.realm.RealmConfiguration;

public class NapsaMarketSalesApplication extends Application {

    @Override
    public void onCreate() {
        super.onCreate();

        Realm.init(this);
        RealmConfiguration configuration = new RealmConfiguration.Builder()
                .name("rewards.realm")
                .deleteRealmIfMigrationNeeded()
                .build();

        //Realm.deleteRealm(configuration);
        Realm.setDefaultConfiguration(configuration);


    }
}
