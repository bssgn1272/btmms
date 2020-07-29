import { Component, OnInit, ViewChild } from "@angular/core";
import {
  MatTableDataSource,
  MatPaginator,
  MatSort,
  MatSnackBar,
  MatDialog,
  MatDialogConfig,
  MatSlideToggleChange,
} from "@angular/material";
import { SettingsService } from "./settings.service";
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { DestinationDayComponent } from "../destination-day/destination-day.component";
import { HttpClient } from "@angular/common/http";
import { ActivatedRoute, Router } from "@angular/router";
import { SlotInteravlService } from "./slot-interval.service";
import { SlotTimeComponent } from "../slot-time/slot-time.component";
import { UpdateSlotTimeComponent } from "../update-slot-time/update-slot-time.component";
import { ModesComponent } from "./components/modes/modes.component";
import { DueTimeComponent } from "./components/due-time/due-time.component";

@Component({
  selector: "app-settings",
  templateUrl: "./settings.component.html",
  styleUrls: ["./settings.component.scss"],
})
export class SettingsComponent implements OnInit {
  // route decleration
  returnUrl: string;
  workFlow: any;
  selectedTime = "";
  description = "";
  dueTime: any;
  // Destination Time formGroup
  destinationForm: FormGroup;

  // Town formGroup
  townForm: FormGroup;

  // Day formGroup
  dayForm: FormGroup;

  // Time formGroup
  timeForm: FormGroup;

  submitted = false;

  // Destinations Time
  displayedColumns: string[] = ["mode", "description", "status"];
  dataSource = new MatTableDataSource([]);

  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;

  // Destinations Time
  displayedDueColumns: string[] = ["due_time", "description", "status"];
  dataDueSource = new MatTableDataSource([]);

  @ViewChild("DuePaginator") duePaginator: MatPaginator;
  @ViewChild("DueSort") dueSort: MatSort;
  days: [];

  // slot interval
  displayedSlotsColumns: string[] = [
    "time",
    "slot_one",
    "slot_two",
    "slot_three",
    "slot_four",
    "slot_five",
    "slot_six",
    "slot_seven",
    "slot_eight",
    "slot_nine",
    "action",
  ];
  slotDataSource = new MatTableDataSource([]);

  @ViewChild("SlotPaginator") slotPaginator: MatPaginator;
  @ViewChild("SlotSort") slotSort: MatSort;
  mode: any;
  modeStatus: any;

  isChecked = "true";
  userItems: any;
  role: any;

  public getFromLocalStrorage() {
    const users = JSON.parse(localStorage.getItem("currentUser"));
    return users;
  }

  constructor(
    private settings: SettingsService,
    private _formBuilder: FormBuilder,
    private dialog: MatDialog,
    private httpClient: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    private _snackBar: MatSnackBar,
    private slots: SlotInteravlService
  ) {
    // Destination Time form Builder
    this.destinationForm = this._formBuilder.group({
      town_name: ["", Validators.required],
      day: ["", Validators.required],
      time_of_day: ["", Validators.required],
    });

    // Destination Town form Builder
    this.townForm = this._formBuilder.group({
      town_name: ["", Validators.required],
    });

    // Day form Builder
    this.dayForm = this._formBuilder.group({
      day: ["", Validators.required],
    });

    // Time form Builder
    this.timeForm = this._formBuilder.group({
      time_of_day: ["", Validators.required],
    });
  }

  ngOnInit() {
    this.userItems = this.getFromLocalStrorage();
    this.role = this.userItems.role;
    this.settings.getModes().then((res) => {
      this.mode = res.data;
      console.log(this.mode);

      this.workFlow = this.mode.filter((x) => x.status === "Active")[0];

      // this.workFlow = this.modeStatus;
      console.log("MODE STATUS", this.workFlow);

      this.dataSource = new MatTableDataSource(res.data);
      this.dataSource.paginator = this.paginator;
      this.dataSource.sort = this.sort;
    });

    this.settings.getDueTimes().then((res) => {
      this.dueTime = res.data;
      console.log(this.dueTime);

      // this.dueTime = res.data.filter((x) => x.status === "Active")[0];

      // this.workFlow = this.modeStatus;
      console.log("DUE TIME>>>>", this.dueTime);

      this.dataDueSource = new MatTableDataSource(this.dueTime);
      this.dataDueSource.paginator = this.duePaginator;
      this.dataDueSource.sort = this.dueSort;
    });

    // fetch slot data
    this.slots.getList().then((res) => {
      this.slotDataSource = new MatTableDataSource(res.data);
      this.slotDataSource.paginator = this.paginator;
      this.slotDataSource.sort = this.sort;
    });

    // get return url from route parameters or default to '/'
    this.returnUrl =
      this.route.snapshot.queryParams["returnUrl"] || "/settings";
  }

  applyFilter(filterValue: string) {
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }

  applyDueFilter(filterValue: string) {
    this.dataDueSource.filter = filterValue.trim().toLowerCase();
  }

  // filter slots
  applySlotFilter(filterValue: string) {
    this.slotDataSource.filter = filterValue.trim().toLowerCase();
  }

  // create destination time
  onOpenDialog() {
    const dialogConfig = new MatDialogConfig();
    // dialogConfig.disableClose = true;
    dialogConfig.autoFocus = true;
    dialogConfig.width = "60%";
    this.dialog.open(ModesComponent, dialogConfig);
  }

  onOpenDueDialog() {
    const dialogConfig = new MatDialogConfig();
    // dialogConfig.disableClose = true;
    dialogConfig.autoFocus = true;
    dialogConfig.width = "60%";
    this.dialog.open(DueTimeComponent, dialogConfig);
  }

  // convenience getter for easy access to form fields
  get f_town() {
    return this.townForm.controls;
  }

  get f_day() {
    return this.dayForm.controls;
  }

  get f_time() {
    return this.timeForm.controls;
  }

  // create town
  onDueTime() {
    // this.submitted = true;

    // stop here if form is invalid
    if (this.townForm.invalid) {
      return;
    }
    this.httpClient
      .post("/api/penalty/time", {
        due_time: this.selectedTime,
        description: this.description,
        status: "Inactive",
      })
      .subscribe(
        (data) => {
          this._snackBar.open("Successfully Created", null, {
            duration: 1000,
            horizontalPosition: "center",
            panelClass: ["blue-snackbar"],
            verticalPosition: "top",
          });
        },
        (error) => {
          this._snackBar.open("Failed", null, {
            duration: 2000,
            horizontalPosition: "center",
            panelClass: ["background-red"],
            verticalPosition: "top",
          });
        }
      );
  }

  // create town
  onSaveTown() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.townForm.invalid) {
      return;
    }
    this.httpClient
      .post("/api/town", {
        town_name: this.f_town.town_name.value,
      })
      .subscribe(
        (data) => {
          this._snackBar.open("Successfully Created", null, {
            duration: 1000,
            horizontalPosition: "center",
            panelClass: ["blue-snackbar"],
            verticalPosition: "top",
          });
        },
        (error) => {
          this._snackBar.open("Failed", null, {
            duration: 2000,
            horizontalPosition: "center",
            panelClass: ["background-red"],
            verticalPosition: "top",
          });
        }
      );
  }

  // create day
  onSaveDay() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.dayForm.invalid) {
      return;
    }
    this.httpClient
      .post("/api/day", {
        day: this.f_day.day.value,
      })
      .subscribe(
        (data) => {
          this._snackBar.open("Successfully Created", null, {
            duration: 1000,
            horizontalPosition: "center",
            panelClass: ["blue-snackbar"],
            verticalPosition: "top",
          });
        },
        (error) => {
          this._snackBar.open("Failed", null, {
            duration: 2000,
            horizontalPosition: "center",
            panelClass: ["background-red"],
            verticalPosition: "top",
          });
        }
      );
  }

  // create time
  onSaveTime() {
    this.submitted = true;

    // stop here if form is invalid
    if (this.timeForm.invalid) {
      return;
    }
    this.httpClient
      .post("/api/time", {
        time_of_day: this.f_time.time_of_day.value,
      })
      .subscribe(
        (data) => {
          this._snackBar.open("Successfully Created", null, {
            duration: 1000,
            horizontalPosition: "center",
            panelClass: ["blue-snackbar"],
            verticalPosition: "top",
          });
        },
        (error) => {
          this._snackBar.open("Failed", null, {
            duration: 2000,
            horizontalPosition: "center",
            panelClass: ["background-red"],
            verticalPosition: "top",
          });
        }
      );
  }

  // Slot time Dialog
  onOpenTimeDialog() {
    const dialogConfig = new MatDialogConfig();
    // dialogConfig.disableClose = true;
    dialogConfig.autoFocus = true;
    dialogConfig.width = "60%";
    this.dialog.open(SlotTimeComponent, dialogConfig);
  }

  onOpenUpdateDialog(row): void {
    const dialogConfig = new MatDialogConfig();
    // dialogConfig.disableClose = true;
    dialogConfig.autoFocus = true;
    dialogConfig.width = "60%";
    dialogConfig.data = { row };
    this.dialog.open(UpdateSlotTimeComponent, dialogConfig);
    this.dialog.afterAllClosed.subscribe((result) => {
      row = result;
    });
  }

  onNWChange(ob: MatSlideToggleChange) {
    ob.checked = false;
    // if (ob.checked) {
    //   this.settings.updateMode(this.workFlow.ID, { status: "Inactive" });
    //   this.workFlow = this.mode.filter((x) => x.mode === "WF")[0];

    //   this.settings.updateMode(this.workFlow.ID, { status: "Active" });
    // } else if (!ob.checked) {
    //   console.log(ob.checked);

    this.settings.updateMode(this.workFlow.ID, { status: "Inactive" });
    this.workFlow = this.mode.filter((x) => x.mode === "NWF")[0];

    this.settings.updateMode(this.workFlow.ID, { status: "Active" });
    // }
  }

  onChange(ob: MatSlideToggleChange) {
    if (ob.checked) {
      this.settings.updateMode(this.workFlow.ID, { status: "Inactive" });
      this.workFlow = this.mode.filter((x) => x.mode === "WF")[0];

      this.settings.updateMode(this.workFlow.ID, { status: "Active" });
    } else if (!ob.checked) {
      console.log(ob.checked);

      this.settings.updateMode(this.workFlow.ID, { status: "Inactive" });
      this.workFlow = this.mode.filter(
        (x) => x.mode === "NWF" && x.status === "Active"
      )[0];

      this.settings.updateMode(this.workFlow.ID, { status: "Active" });
    }
  }

  onDueChange(due) {
    console.log("DUE>>>>>", due);
    for (let i = 0; i < this.dueTime.length; i++) {
      this.settings.updateDueTime(this.dueTime[i].ID, { status: "Inactive" });
    }
    this.settings.updateDueTime(due.ID, { status: "Active" });
    this.settings.updateDueTime(due.ID, { status: "Active" });
  }
}
