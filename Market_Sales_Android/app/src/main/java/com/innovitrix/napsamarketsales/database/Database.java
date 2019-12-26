package com.innovitrix.napsamarketsales.database;

import android.util.Log;

import com.innovitrix.napsamarketsales.models.Route;

import java.util.List;

import io.realm.Realm;
import io.realm.RealmResults;

public class Database {

    private static final String TAG = Database.class.getSimpleName();

    private Realm realm;//a field to reference our Realm instance

    public void open() {
        realm = Realm.getDefaultInstance();

        Log.d(TAG, "open: Database opened");
    }

    public void close() {
        realm.close();

        Log.d(TAG, "close: Database closed");
    }

    //----------------------------------------ROUTES--------------------------------------------//
    //INSERTING INTO TABLE
    public void createRoutes(final Route route) {
        realm.executeTransactionAsync(new Realm.Transaction() {
            @Override
            public void execute(Realm realm) {
                realm.insertOrUpdate(route);
            }
        });

        Log.d(TAG, "createdReward: the id: " + route.getRoute_id());
    }

    //FETCHGIN ALL FROM TABLE
    public List<Route> getAllRoutes()
    {
        return realm
                .where(Route.class)
                .findAll();

    }

    //GET PARTICULAR ROUTE FROM TABLE
    public Route getRoute(int route_id)
    {
        return realm
                .where(Route.class)
                .equalTo("route_id",route_id)
                .findFirst();

    }


    //DELETE ROUTES FROM TABLE
    public void deleteAllRoutes()
    {
        final RealmResults<Route> routes = realm
                .where(Route.class)
                .findAll();//instead of deleting a single record, we want to delete all of these records

        realm.executeTransaction(new Realm.Transaction()
        {
            @Override
            public void execute(Realm realm)
            {
                routes.deleteAllFromRealm();
            }
        });
    }
    //----------------------------------------END OF ROUTES--------------------------------------------//
}
