import { Component, OnInit, ViewChild } from '@angular/core';
import { MatPaginator, MatSort, MatTableDataSource } from '@angular/material';
import { DatePipe } from '@angular/common';
import {FormControl, FormGroup} from '@angular/forms';
import {SalesService} from './sales.service';
import { SalesExportService } from './salesExport.service';
import {NotDispatchedService} from './notdispatched.service'
import { CollectedService } from './collected.service';
import { UncollectedService } from './uncollected.service';
import { LostService } from './lost.service';


@Component({
  selector: 'app-typography',
  templateUrl: './typography.component.html',
  styleUrls: ['./typography.component.css']
})
export class TypographyComponent implements OnInit {

  // sales
  displayedColumnsSales: string[] = [ 'station', 'actual_sales', 'target_sales', 'archived', 'date'];
  dataSourceSales = new MatTableDataSource(this.salesService.getAllSales());
  pipe: DatePipe;

  // Not Yet Dispatched
  displayedColumnsNotDispatched: string[] = [ 'receipt_no', 'parcel_name', 'destination', 'origin_station', 'status', 'sender_name',
    'sender_phone', 'receiver_name', 'receiver_phone', 'date_sent'];
  dataSourceNotDispatched = new MatTableDataSource(this.notDispatchedService.getAllNotDispatched());
   pipeNotDispatched: DatePipe;


  // collected
  displayedColumnsCollected: string[] = [ 'receipt_no', 'parcel_name', 'destination', 'origin_station', 'status', 'sender_name',
    'sender_phone', 'receiver_name', 'receiver_phone', 'date_sent'];
  dataSourceCollected = new MatTableDataSource(this.collectedService.getAllCollected());
  pipeCollected: DatePipe;


  // Uncollected
  displayedColumnsUncollected: string[] = [ 'receipt_no', 'parcel_name', 'destination', 'origin_station', 'status', 'sender_name',
    'sender_phone', 'receiver_name', 'receiver_phone', 'date_sent'];
 /* dataSourceUncollected = new MatTableDataSource(this.uncollectedService.getAllUncollected());
  pipeUncollected: DatePipe;
*/
  // Lost
  displayedColumnsLost: string[] = [ 'receipt_no', 'parcel_name', 'destination', 'origin_station', 'status', 'sender_name',
    'sender_phone', 'receiver_name', 'receiver_phone', 'date_sent'];
 /* dataSourceLost = new MatTableDataSource(this.lostService.getAllLost());
  pipeLost: DatePipe;*/


   // sales
  filterForm = new FormGroup({
    fromDate: new FormControl(),
    toDate: new FormControl(),
  });


  get fromDate() { return this.filterForm.get('fromDate').value; }
  get toDate() { return this.filterForm.get('toDate').value; }


  // Not Yet Dispatched
  filterFormNotDispatched = new FormGroup({
    fromDateNotDispatched: new FormControl(),
    toDateNotDispatched: new FormControl(),
  });


  get fromDateNotDispatched() { return this.filterFormNotDispatched.get('fromDateNotDispatched').value; }
  get toDateNotDispatched() { return this.filterFormNotDispatched.get('toDateNotDispatched').value; }


  // Collected
  filterFormCollected = new FormGroup({
    fromDateCollected: new FormControl(),
    toDateCollected: new FormControl(),
  });


  get fromDateCollected() { return this.filterFormCollected.get('fromDateCollected').value; }
  get toDateCollected() { return this.filterFormCollected.get('toDateCollected').value; }

/*
  // Uncollected
  filterFormUncollected = new FormGroup({
    fromDateUncollected: new FormControl(),
    toDateUncollected: new FormControl(),
  });


  get fromDateUncollected() { return this.filterFormUncollected.get('fromDateUncollected').value; }
  get toDateUncollected() { return this.filterFormUncollected.get('toDateUncollected').value; }


  // Lost
  filterFormLost = new FormGroup({
    fromDateLost: new FormControl(),
    toDateLost: new FormControl(),
  });


  get fromDateLost() { return this.filterFormLost.get('fromDateLost').value; }
  get toDateLost() { return this.filterFormLost.get('toDateLost').value; }
*/

// sales
  @ViewChild(MatPaginator) paginatorSales: MatPaginator;
  @ViewChild(MatSort) sortSales: MatSort;
  @ViewChild('dataTable') table;
  dataTable: any;
  dtOptions: any;

// Not yet Dispatched
  @ViewChild('paginatorNotDispatched') paginatorNotDispatched: MatPaginator;
  @ViewChild(MatSort) sortNotDispatched: MatSort;
  @ViewChild('dataTableNotDispatched') tableNotDispatched;
  dataTableNotDispatched: any;


  // Collected
  @ViewChild('paginatorCollected') paginatorCollected: MatPaginator;
  @ViewChild(MatSort) sortCollected: MatSort;
  @ViewChild('dataTableCollected') tableCollected;
  dataTableCollected: any;
/*
  // Uncollected
  @ViewChild('paginatorUncollected') paginatorUncollected: MatPaginator;
  @ViewChild(MatSort) sortUncollected: MatSort;
  @ViewChild('dataTableUncollected') tableUncollected;
  dataTableUncollected: any;


  // Lost
  @ViewChild('paginatorLost') paginatorLost: MatPaginator;
  @ViewChild(MatSort) sortLost: MatSort;
  @ViewChild('dataTableLost') tableLost;
  dataTableLost: any;*/

  constructor(/*private lostService: LostService,*/ private collectedService: CollectedService,
              /*private uncollectedService: UncollectedService,*/
              private salesService: SalesService, private excelService: SalesExportService,
              private notDispatchedService: NotDispatchedService) {

    // sales
    this.pipe = new DatePipe('en');
    this.dataSourceSales.filterPredicate = (data, filter) => {
      if (this.fromDate && this.toDate) {
        return data.date >= this.fromDate && data.date <= this.toDate;
      }
      return true;
    }



    // Not Yet Dispatched
    this.pipeNotDispatched = new DatePipe('en');
    this.dataSourceNotDispatched.filterPredicate = (data, filter) => {
      if (this.fromDateNotDispatched && this.toDateNotDispatched) {
        return data.date_sent >= this.fromDateNotDispatched && data.date_sent <= this.toDateNotDispatched;
      }
      return true;
    }


    // Collected
    this.pipeCollected = new DatePipe('en');
    this.dataSourceCollected.filterPredicate = (data, filter) => {
      if (this.fromDateCollected && this.toDateCollected) {
        return data.date_sent >= this.fromDateCollected && data.date_sent <= this.toDateCollected;
      }
      return true;
    }


   /* // Uncollected
    this.pipeUncollected = new DatePipe('en');
    this.dataSourceUncollected.filterPredicate = (data, filter) => {
      if (this.fromDateUncollected && this.toDateUncollected) {
        return data.date_sent >= this.fromDateUncollected && data.date_sent <= this.toDateUncollected;
      }
      return true;
    }


    // Lost
    this.pipeLost = new DatePipe('en');
    this.dataSourceLost.filterPredicate = (data, filter) => {
      if (this.fromDateLost && this.toDateLost) {
        return data.date_sent >= this.fromDateLost && data.date_sent <= this.toDateLost;
      }
      return true;
    }*/


    /*  this.sName = 'SheetTest';
      this.excelFileName = 'TestExcelExport.xlsx';*/
  }

  ngOnInit() {
    this.dataSourceSales.paginator = this.paginatorSales;
    this.dataSourceSales.sort = this.sortSales;

    this.dataSourceNotDispatched.paginator = this.paginatorNotDispatched;
    this.dataSourceNotDispatched.sort = this.sortNotDispatched;

    this.dataSourceCollected.paginator = this.paginatorCollected;
    this.dataSourceCollected.sort = this.sortCollected;


    /*this.dataSourceUncollected.paginator = this.paginatorUncollected;
    this.dataSourceUncollected.sort = this.sortUncollected;


    this.dataSourceLost.paginator = this.paginatorLost;
    this.dataSourceLost.sort = this.sortLost;*/
  }

  // Sales
    onExportXLSX(): void {
        this.excelService.exportToExcel(this.dataSourceSales.filteredData, 'sales');
    }

  onExportXLSXNotDispatched(): void {
    this.excelService.exportToExcel(this.dataSourceNotDispatched.filteredData, 'not_yet_dispatched')
  }


  onExportXLSXCollected(): void {
    this.excelService.exportToExcel(this.dataSourceCollected.filteredData, 'collected')
  }


  /*onExportXLSXUncollected(): void {
    this.excelService.exportToExcel(this.dataSourceUncollected.filteredData, 'uncollected')
  }


  onExportXLSXLost(): void {
    this.excelService.exportToExcel(this.dataSourceLost.filteredData, 'lost')
  }*/



  applyFilter(filterValue: string) {
    this.dataSourceSales.filter = filterValue.trim().toLowerCase();
  }

  applyDateFilter() {
    // sales
    this.dataSourceSales.filter = '' + Math.random();
  }

  applyDateFilterNotDispatched() {
    // Not Dispatched
    this.dataSourceNotDispatched.filter = '' + Math.random();
  }


  applyDateFilterCollected() {
    // Collected
    this.dataSourceCollected.filter = '' + Math.random();
  }


  /*applyDateFilterUncollected() {
    // Not Collected
    this.dataSourceUncollected.filter = '' + Math.random();
  }


  applyDateFilterLost() {
    // Not Collected
    this.dataSourceLost.filter = '' + Math.random();
  }*/

}
