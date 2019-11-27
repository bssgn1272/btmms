import { Injectable } from '@angular/core';
import { Sales } from './sales';

const All_SALES: Sales[] = [
    { station: 'Intercity',  actual_sales: '26390', target_sales: '39000', archived: '68%', date: new Date(2018, 1, 24)},
    { station: 'Oasis',  actual_sales: '17310', target_sales: '18000', archived: '96%', date: new Date(2018, 5, 24)},
    { station: 'Kabwe',  actual_sales: '3710', target_sales: '6000', archived: '62%', date: new Date(2018, 6, 24)},
    { station: 'Ndola',  actual_sales: '9340', target_sales: '10800', archived: '86%', date: new Date(2018, 7, 24)},
    { station: 'Kitwe',  actual_sales: '9220', target_sales: '10800', archived: '85', date: new Date(2018, 9, 24)},
    { station: 'Chingola',  actual_sales: '1940', target_sales: '3000', archived: '65', date: new Date(2018, 10, 24)},
    { station: 'Chililabombwe',  actual_sales: '2370', target_sales: '3000', archived: '79%', date: new Date(2018, 11, 24)},
    { station: 'Mazabuka',  actual_sales: '1270', target_sales: '3000', archived: '42%', date: new Date(2019, 1, 24)},
    { station: 'Monze',  actual_sales: '1900', target_sales: '3000', archived: '63%', date: new Date(2019, 2, 24)},
    { station: 'Choma',  actual_sales: '3300', target_sales: '6000', archived: '55%', date: new Date(2019, 3, 24)}
];

@Injectable({
    providedIn: 'root'
})
export class SalesService {
    getAllSales() {
        return All_SALES;
    }
}
