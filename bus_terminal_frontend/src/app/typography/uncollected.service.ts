import { Injectable } from '@angular/core';
import { Uncollected } from './uncollected';

const All_UNCOLLECTED: Uncollected[] = [
    { receipt_no: 'msc05280001', parcel_name: 'bag', destination: 'lusaka', origin_station: 'livingstone',
        status: 'pending receiving', sender_name: 'Joe Mbuzi', sender_phone: '097239339328', receiver_name: 'niza T',
        receiver_phone: '0968332265', date_sent: new Date(2019, 1, 23)},
    { receipt_no: 'msc05280002', parcel_name: 'bag', destination: 'lusaka', origin_station: 'livingstone',
        status: 'pending receiving', sender_name: 'Joe Mbuzi', sender_phone: '097239339328', receiver_name: 'niza T',
        receiver_phone: '0968332265', date_sent: new Date(2019, 1, 24)},
    { receipt_no: 'msc05280003', parcel_name: 'bag', destination: 'lusaka', origin_station: 'livingstone',
        status: 'pending receiving', sender_name: 'Joe Mbuzi', sender_phone: '097239339328', receiver_name: 'niza T',
        receiver_phone: '0968332265', date_sent: new Date(2019, 1, 24)},
    { receipt_no: 'msc05280004', parcel_name: 'bag', destination: 'lusaka', origin_station: 'livingstone',
        status: 'pending receiving', sender_name: 'Joe Mbuzi', sender_phone: '097239339328', receiver_name: 'niza T',
        receiver_phone: '0968332265', date_sent: new Date(2019, 1, 24)},
    { receipt_no: 'msc05280005', parcel_name: 'bag', destination: 'lusaka', origin_station: 'livingstone',
        status: 'pending receiving', sender_name: 'Joe Mbuzi', sender_phone: '097239339328', receiver_name: 'niza T',
        receiver_phone: '0968332265', date_sent: new Date(2019, 1, 24)},
    { receipt_no: 'msc05280006', parcel_name: 'bag', destination: 'lusaka', origin_station: 'livingstone',
        status: 'pending receiving', sender_name: 'Joe Mbuzi', sender_phone: '097239339328', receiver_name: 'niza T',
        receiver_phone: '0968332265', date_sent: new Date(2019, 1, 24)},
    { receipt_no: 'msc05280007', parcel_name: 'bag', destination: 'lusaka', origin_station: 'livingstone',
        status: 'pending receiving', sender_name: 'Joe Mbuzi', sender_phone: '097239339328', receiver_name: 'niza T',
        receiver_phone: '0968332265', date_sent: new Date(2019, 1, 24)},
    { receipt_no: 'msc05280008', parcel_name: 'bag', destination: 'lusaka', origin_station: 'livingstone',
        status: 'pending receiving', sender_name: 'Joe Mbuzi', sender_phone: '097239339328', receiver_name: 'niza T',
        receiver_phone: '0968332265', date_sent: new Date(2019, 1, 24)},
    { receipt_no: 'msc05280009', parcel_name: 'bag', destination: 'lusaka', origin_station: 'livingstone',
        status: 'pending receiving', sender_name: 'Joe Mbuzi', sender_phone: '097239339328', receiver_name: 'niza T',
        receiver_phone: '0968332265', date_sent: new Date(2019, 1, 24)},
    { receipt_no: 'msc052800010', parcel_name: 'bag', destination: 'lusaka', origin_station: 'livingstone',
        status: 'pending receiving', sender_name: 'Joe Mbuzi', sender_phone: '097239339328', receiver_name: 'niza T',
        receiver_phone: '0968332265', date_sent: new Date(2018, 1, 24)},
];

@Injectable({
    providedIn: 'root'
})
export class UncollectedService {
    getAllUncollected() {
        return All_UNCOLLECTED;
    }
}
