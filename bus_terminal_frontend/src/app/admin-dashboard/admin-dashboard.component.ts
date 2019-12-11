import { Component, OnInit, ViewChild } from '@angular/core';
import { MatTableDataSource, MatPaginator, MatSort, MatSnackBar } from '@angular/material';
import { FormBuilder, Validators, FormGroup, FormControl } from '@angular/forms';
import { HttpClient } from '@angular/common/http';
import { ActivatedRoute, Router } from '@angular/router';

import * as reque from './admin-dashboard'

export interface Time {
  time: string;
}




export interface Req {
  r_id: number;
  status: string;
  username: string;
}






@Component({
  selector: 'app-admin-dashboard',
  templateUrl: './admin-dashboard.component.html',
  styleUrls: ['./admin-dashboard.component.scss']
})
export class AdminDashboardComponent implements OnInit {
  public slot_one_five = 0;
  public reqs: Req[] = [];

  // Slot One
  public req15s: reque.Req15[] = [];
  public req16s: reque.Req16[] = [];
  public req17s: reque.Req17[] = [];
  public req18s: reque.Req18[] = [];
  public req19s: reque.Req19[] = [];
  public req1s10: reque.Req110[] = [];
  public req111s: reque.Req111[] = [];
  public req112s: reque.Req112[] = [];
  public req113s: reque.Req113[] = [];
  public req114s: reque.Req114[] = [];
  public req115s: reque.Req115[] = [];

  public requ15es: any[];
  public requ16s: any[];
  public requ17s: any[];
  public requ18s: any[];
  public requ19s: any[];
  public requ110s: any[];
  public requ111s: any[];
  public requ112s: any[];
  public requ113s: any[];
  public requ114s: any[];
  public requ115s: any[];

  // Slot Two
  public req25s: reque.Req25[] = [];
  public req26s: reque.Req26[] = [];
  public req27s: reque.Req27[] = [];
  public req28s: reque.Req28[] = [];
  public req29s: reque.Req29[] = [];
  public req2s10: reque.Req210[] = [];
  public req211s: reque.Req211[] = [];
  public req212s: reque.Req212[] = [];
  public req213s: reque.Req213[] = [];
  public req214s: reque.Req214[] = [];
  public req215s: reque.Req215[] = [];

  public requ25s: any[];
  public requ26s: any[];
  public requ27s: any[];
  public requ28s: any[];
  public requ29s: any[];
  public requ210s: any[];
  public requ211s: any[];
  public requ212s: any[];
  public requ213s: any[];
  public requ214s: any[];
  public requ215s: any[];

  // Slot Three
  public req35s: reque.Req35[] = [];
  public req36s: reque.Req36[] = [];
  public req37s: reque.Req37[] = [];
  public req38s: reque.Req38[] = [];
  public req39s: reque.Req39[] = [];
  public req3s10: reque.Req310[] = [];
  public req311s: reque.Req311[] = [];
  public req312s: reque.Req312[] = [];
  public req313s: reque.Req213[] = [];
  public req314s: reque.Req314[] = [];
  public req315s: reque.Req315[] = [];

  public requ35s: any[];
  public requ36s: any[];
  public requ37s: any[];
  public requ38s: any[];
  public requ39s: any[];
  public requ310s: any[];
  public requ311s: any[];
  public requ312s: any[];
  public requ313s: any[];
  public requ314s: any[];
  public requ315s: any[];

  // Slot Four
  public req45s: reque.Req45[] = [];
  public req46s: reque.Req46[] = [];
  public req47s: reque.Req47[] = [];
  public req48s: reque.Req48[] = [];
  public req49s: reque.Req49[] = [];
  public req4s10: reque.Req410[] = [];
  public req411s: reque.Req411[] = [];
  public req412s: reque.Req412[] = [];
  public req413s: reque.Req413[] = [];
  public req414s: reque.Req414[] = [];
  public req415s: reque.Req415[] = [];

  public requ45s: any[];
  public requ46s: any[];
  public requ47s: any[];
  public requ48s: any[];
  public requ49s: any[];
  public requ410s: any[];
  public requ411s: any[];
  public requ412s: any[];
  public requ413s: any[];
  public requ414s: any[];
  public requ415s: any[];

  // Slot Five
  public req55s: reque.Req55[] = [];
  public req56s: reque.Req56[] = [];
  public req57s: reque.Req57[] = [];
  public req58s: reque.Req58[] = [];
  public req59s: reque.Req59[] = [];
  public req5s10: reque.Req510[] = [];
  public req511s: reque.Req511[] = [];
  public req512s: reque.Req512[] = [];
  public req513s: reque.Req513[] = [];
  public req514s: reque.Req514[] = [];
  public req515s: reque.Req515[] = [];

  public requ55s: any[];
  public requ56s: any[];
  public requ57s: any[];
  public requ58s: any[];
  public requ59s: any[];
  public requ510s: any[];
  public requ511s: any[];
  public requ512s: any[];
  public requ513s: any[];
  public requ514s: any[];
  public requ515s: any[];

  summaries: any[];
  req110s: any[];
  req210s: any[];
  req310s: any[];
  req410s: any;
  req510s: any[];
  requ11s: any[];

  // Slot One Declaration
  slot15RequestFormGroup: FormGroup;
  slot115RequestFormGroup: FormGroup;
  slot16RequestFormGroup: FormGroup;
  slot17RequestFormGroup: FormGroup;
  slot18RequestFormGroup: FormGroup;
  slot19RequestFormGroup: FormGroup;
  slot110RequestFormGroup: FormGroup;
  slot111RequestFormGroup: FormGroup;
  slot112RequestFormGroup: FormGroup;
  slot113RequestFormGroup: FormGroup;
  slot114RequestFormGroup: FormGroup;

  // Slot Two Declaration
  slot25RequestFormGroup: FormGroup;
  slot215RequestFormGroup: FormGroup;
  slot26RequestFormGroup: FormGroup;
  slot27RequestFormGroup: FormGroup;
  slot28RequestFormGroup: FormGroup;
  slot29RequestFormGroup: FormGroup;
  slot210RequestFormGroup: FormGroup;
  slot211RequestFormGroup: FormGroup;
  slot212RequestFormGroup: FormGroup;
  slot213RequestFormGroup: FormGroup;
  slot214RequestFormGroup: FormGroup;

  // Slot Three Declaration
  slot35RequestFormGroup: FormGroup;
  slot315RequestFormGroup: FormGroup;
  slot36RequestFormGroup: FormGroup;
  slot37RequestFormGroup: FormGroup;
  slot38RequestFormGroup: FormGroup;
  slot39RequestFormGroup: FormGroup;
  slot310RequestFormGroup: FormGroup;
  slot311RequestFormGroup: FormGroup;
  slot312RequestFormGroup: FormGroup;
  slot313RequestFormGroup: FormGroup;
  slot314RequestFormGroup: FormGroup;

  // Slot Four Declaration
  slot45RequestFormGroup: FormGroup;
  slot415RequestFormGroup: FormGroup;
  slot46RequestFormGroup: FormGroup;
  slot47RequestFormGroup: FormGroup;
  slot48RequestFormGroup: FormGroup;
  slot49RequestFormGroup: FormGroup;
  slot410RequestFormGroup: FormGroup;
  slot411RequestFormGroup: FormGroup;
  slot412RequestFormGroup: FormGroup;
  slot413RequestFormGroup: FormGroup;
  slot414RequestFormGroup: FormGroup;

  // Slot Five Declaration
  slot55RequestFormGroup: FormGroup;
  slot515RequestFormGroup: FormGroup;
  slot56RequestFormGroup: FormGroup;
  slot57RequestFormGroup: FormGroup;
  slot58RequestFormGroup: FormGroup;
  slot59RequestFormGroup: FormGroup;
  slot510RequestFormGroup: FormGroup;
  slot511RequestFormGroup: FormGroup;
  slot512RequestFormGroup: FormGroup;
  slot513RequestFormGroup: FormGroup;
  slot514RequestFormGroup: FormGroup;

  id = 0;
  status = 'A';
  disableSelect = false;
  user = '';
  slot = '';
  time: any;
  slot_one: any;
  request: any;

  constructor(
    private _formBuilder: FormBuilder,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar
  ) {}

  async ngOnInit() {
    // Slot One Form Group
    this.slot15RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot16RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot17RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot18RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot19RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot110RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot111RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot112RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot113RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot114RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot115RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    // Slot Two Form Group
    this.slot25RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot26RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot27RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot28RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot29RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot210RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot211RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot212RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot213RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot214RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot215RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    // Slot Three Form Group
    this.slot35RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot36RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot37RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot38RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot39RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot310RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot311RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot312RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot313RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot314RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot315RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    // Slot Four Form Group
    this.slot45RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot46RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot47RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot48RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot49RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot410RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot411RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot412RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot413RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot414RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot415RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    // Slot Five Form Group
    this.slot55RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot56RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot57RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot58RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot59RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot510RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot511RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot512RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot513RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot514RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });

    this.slot515RequestFormGroup = this._formBuilder.group({
      request: [null, [Validators.required]],
      time: [null, [Validators.required]]
    });



    // slot One
    await this.loadSlotOneFive();
    await this.loadSlotOneSix();
    await this.loadSlotOneSeven();
    await this.loadSlotOneEight();
    await this.loadSlotOneNine();
    await this.loadSlotOneTen();
    await this.loadSlotOneEleven();
    await this.loadSlotOneTwelve();
    await this.loadSlotOneThirteen();
    await this.loadSlotOneFourteen();
    await this.loadSlotOneFifteen();

    // slot Two
    await this.loadSlotTwoFive();
    await this.loadSlotTwoSix();
    await this.loadSlotTwoSeven();
    await this.loadSlotTwoEight();
    await this.loadSlotTwoNine();
    await this.loadSlotTwoTen();
    await this.loadSlotTwoEleven();
    await this.loadSlotTwoTwelve();
    await this.loadSlotTwoThirteen();
    await this.loadSlotTwoFourteen();
    await this.loadSlotTwoFifteen();

    // slot Three
    await this.loadSlotThreeFive();
    await this.loadSlotThreeSix();
    await this.loadSlotThreeSeven();
    await this.loadSlotThreeEight();
    await this.loadSlotThreeNine();
    await this.loadSlotThreeTen();
    await this.loadSlotThreeEleven();
    await this.loadSlotThreeTwelve();
    await this.loadSlotThreeThirteen();
    await this.loadSlotThreeFourteen();
    await this.loadSlotThreeFifteen();

    // slot Four
    await this.loadSlotFourFive();
    await this.loadSlotFourSix();
    await this.loadSlotFourSeven();
    await this.loadSlotFourEight();
    await this.loadSlotFourNine();
    await this.loadSlotFourTen();
    await this.loadSlotFourEleven();
    await this.loadSlotFourTwelve();
    await this.loadSlotFourThirteen();
    await this.loadSlotFourFourteen();
    await this.loadSlotFourFifteen();

    // slot Five
    await this.loadSlotFiveFive();
    await this.loadSlotFiveSix();
    await this.loadSlotFiveSeven();
    await this.loadSlotFiveEight();
    await this.loadSlotFiveNine();
    await this.loadSlotFiveTen();
    await this.loadSlotFiveEleven();
    await this.loadSlotFiveTwelve();
    await this.loadSlotFiveThirteen();
    await this.loadSlotFiveFourteen();
    await this.loadSlotFiveFifteen();
  }

  // fetch Slot One Requests

  async loadSlotOneFive() {
    this.req15s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/five')
      .toPromise();

    this.requ15es = this.req15s.data;
  }

  async loadSlotOneSix() {
    this.req16s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/six')
      .toPromise();
    this.requ16s = this.req16s.data;
  }

  async loadSlotOneSeven() {
    this.req17s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/seven')
      .toPromise();
    this.requ17s = this.req17s.data;
  }

  async loadSlotOneEight() {
    this.req18s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/eight')
      .toPromise();
    this.requ18s = this.req18s.data;
  }

  async loadSlotOneNine() {
    this.req19s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/nine')
      .toPromise();
    this.requ19s = this.req19s.data;
  }

  async loadSlotOneTen() {
    this.req110s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/ten')
      .toPromise();
    this.requ110s = this.req110s.data;
  }

  async loadSlotOneEleven() {
    this.req111s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/eleven')
      .toPromise();
    this.requ111s = this.req111s.data;
  }

  async loadSlotOneTwelve() {
    this.req112s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/twelve')
      .toPromise();
    this.requ112s = this.req112s.data;
  }

  async loadSlotOneThirteen() {
    this.req113s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/thirteen')
      .toPromise();
    this.requ113s = this.req113s.data;
  }

  async loadSlotOneFourteen() {
    this.req114s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/fourteen')
      .toPromise();
    this.requ114s = this.req114s.data;
  }

  async loadSlotOneFifteen() {
    this.req115s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_one/fifteen')
      .toPromise();
    this.requ115s = this.req115s.data;
  }

  // fetch Slot Two Requests

  async loadSlotTwoFive() {
    this.req25s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_two/five')
      .toPromise();
    this.requ25s = this.req25s.data;
  }

  async loadSlotTwoSix() {
    this.req26s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_two/six')
      .toPromise();
    this.requ26s = this.req26s.data;
  }

  async loadSlotTwoSeven() {
    this.req27s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_two/seven')
      .toPromise();
    this.requ27s = this.req27s.data;
  }

  async loadSlotTwoEight() {
    this.req28s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_two/eight')
      .toPromise();
    this.requ28s = this.req28s.data;
  }

  async loadSlotTwoNine() {
    this.req29s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_two/nine')
      .toPromise();
    this.requ29s = this.req29s.data;
  }

  async loadSlotTwoTen() {
    this.req210s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_two/ten')
      .toPromise();
    this.requ210s = this.req210s.data;
  }

  async loadSlotTwoEleven() {
    this.req211s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_two/eleven')
      .toPromise();
    this.requ211s = this.req211s.data;
  }

  async loadSlotTwoTwelve() {
    this.req212s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_two/twelve')
      .toPromise();
    this.requ212s = this.req212s.data;
  }

  async loadSlotTwoThirteen() {
    this.req213s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_two/thirteen')
      .toPromise();
    this.requ213s = this.req213s.data;
  }

  async loadSlotTwoFourteen() {
    this.req214s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_Three/fourteen')
      .toPromise();
    this.requ214s = this.req214s.data;
  }

  async loadSlotTwoFifteen() {
    this.req215s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_Three/fifteen')
      .toPromise();
    this.requ215s = this.req215s.data;
  }

  // fetch Slot Three Requests

  async loadSlotThreeFive() {
    this.req35s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_three/five')
      .toPromise();
    this.requ35s = this.req35s.data;
  }

  async loadSlotThreeSix() {
    this.req36s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_three/six')
      .toPromise();
    this.requ36s = this.req36s.data;
  }

  async loadSlotThreeSeven() {
    this.req37s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_three/seven')
      .toPromise();
    this.requ37s = this.req37s.data;
  }

  async loadSlotThreeEight() {
    this.req38s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_three/eight')
      .toPromise();
    this.requ38s = this.req38s.data;
  }

  async loadSlotThreeNine() {
    this.req39s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_three/nine')
      .toPromise();
    this.requ39s = this.req39s.data;
  }

  async loadSlotThreeTen() {
    this.req310s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_three/ten')
      .toPromise();
    this.requ310s = this.req310s.data;
  }

  async loadSlotThreeEleven() {
    this.req311s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_three/eleven')
      .toPromise();
    this.requ311s = this.req311s.data;
  }

  async loadSlotThreeTwelve() {
    this.req312s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_three/twelve')
      .toPromise();
    this.requ312s = this.req312s.data;
  }

  async loadSlotThreeThirteen() {
    this.req313s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_three/thirteen')
      .toPromise();
    this.requ313s = this.req313s.data;
  }

  async loadSlotThreeFourteen() {
    this.req314s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_three/fourteen')
      .toPromise();
    this.requ314s = this.req314s.data;
  }

  async loadSlotThreeFifteen() {
    this.req315s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_thre/fifteen')
      .toPromise();
    this.requ315s = this.req315s.data;
  }

  // fetch Slot Four Requests

  async loadSlotFourFive() {
    this.req45s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/five')
      .toPromise();
    this.requ45s = this.req45s.data;
  }

  async loadSlotFourSix() {
    this.req46s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/six')
      .toPromise();
    this.requ46s = this.req46s.data;
  }

  async loadSlotFourSeven() {
    this.req47s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/seven')
      .toPromise();
    this.requ47s = this.req47s.data;
  }

  async loadSlotFourEight() {
    this.req48s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/eight')
      .toPromise();
    this.requ48s = this.req48s.data;
  }

  async loadSlotFourNine() {
    this.req49s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/nine')
      .toPromise();
    this.requ49s = this.req49s.data;
  }

  async loadSlotFourTen() {
    this.requ410s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/ten')
      .toPromise();
    this.requ410s = this.req410s.data;
  }

  async loadSlotFourEleven() {
    this.req411s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/eleven')
      .toPromise();
    this.requ411s = this.req411s.data;
  }

  async loadSlotFourTwelve() {
    this.req412s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/twelve')
      .toPromise();
    this.requ412s = this.req412s.data;
  }

  async loadSlotFourThirteen() {
    this.req413s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/thirteen')
      .toPromise();
    this.requ413s = this.req413s.data;
  }

  async loadSlotFourFourteen() {
    this.req414s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/fourteen')
      .toPromise();
    this.requ414s = this.req414s.data;
  }

  async loadSlotFourFifteen() {
    this.req415s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/fifteen')
      .toPromise();
    this.requ415s = this.req415s.data;
  }

  // fetch Slot Five Requests

  async loadSlotFiveFive() {
    this.req55s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_four/five')
      .toPromise();
    this.requ55s = this.req55s.data;
  }

  async loadSlotFiveSix() {
    this.req56s = await this.httpClient
      .get<Req[]>('/api/reservations/requests/slot_five/six')
      .toPromise();
    this.requ56s = this.req56s.data;
  }

  async loadSlotFiveSeven() {
    this.req57s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_five/seven')
      .toPromise();
    this.requ57s = this.req57s.data;
  }

  async loadSlotFiveEight() {
    this.req58s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_five/eight')
      .toPromise();
    this.requ58s = this.req58s.data;
  }

  async loadSlotFiveNine() {
    this.req59s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_five/nine')
      .toPromise();
    this.requ59s = this.req59s.data;
  }

  async loadSlotFiveTen() {
    this.req510s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_five/ten')
      .toPromise();
    this.requ510s = this.req510s.data;
  }

  async loadSlotFiveEleven() {
    this.req511s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_five/eleven')
      .toPromise();
    this.requ11s = this.req511s.data;
  }

  async loadSlotFiveTwelve() {
    this.req512s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_five/twelve')
      .toPromise();
    this.requ512s = this.req512s.data;
  }

  async loadSlotFiveThirteen() {
    this.req513s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_five/thirteen')
      .toPromise();
    this.requ513s = this.req513s.data;
  }

  async loadSlotFiveFourteen() {
    this.req514s = await this.httpClient
      .get<any[]>('/api/reservations/requests/slot_five/fourteen')
      .toPromise();
    this.requ514s = this.req514s.data;
  }

  async loadSlotFiveFifteen() {
    this.req515s = await this.httpClient
      .get<Req[]>('/api/reservations/requests/slot_five/fifteen')
      .toPromise();
    this.requ515s = this.req515s.data;
  }

  // Slot One Approve

  approve15(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve16(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve17(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve18(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve19(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve110(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve111(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve112(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve113(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve114(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve115(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  // Slot Two Approve

  approve25(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve26(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve27(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve28(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve29(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve210(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve211(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve212(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve213(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve214(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve215(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  // Slot Three Approve

  approve35(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve36(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve37(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve38(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve39(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve310(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve311(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve312(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve313(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve314(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve315(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  // Slot Four Approve

  approve45(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve46(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve47(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve48(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve49(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve410(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve411(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve412(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve413(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve414(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve415(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  // Slot Five Approve

  approve55(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve56(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve57(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve58(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve59(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve510(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve511(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve512(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve513(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
  approve514(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }

  approve515(request) {
    this.id = request.r_id;
    this.user = request.username;
    this.slot_one = request.slot;
    this.time = request.time;
    console.log(this.id);

    this.httpClient
      .put('/api/slots/close', {
        time: this.time,
        slot_one: this.user
      })
      .toPromise();
    this.httpClient
      .put('/api/approve/reservations/requests/' + this.id, {
        status: this.status
      })
      .subscribe(
        data => {
          this._snackBar.open('Successfully Updated', null, {
            duration: 1000,
            horizontalPosition: 'center',
            panelClass: ['blue-snackbar'],
            verticalPosition: 'top'
          });
        },
        error => {
          this._snackBar.open('Failed', null, {
            duration: 2000,
            horizontalPosition: 'center',
            panelClass: ['background-red'],
            verticalPosition: 'top'
          });
        }
      );
  }
}
