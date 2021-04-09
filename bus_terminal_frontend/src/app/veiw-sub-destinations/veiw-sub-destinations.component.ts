import {Component, Inject, OnInit, Optional} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {ViewSlotsService} from '../view-my-slots/view-slots.service';

@Component({
  selector: 'app-veiw-sub-destinations',
  templateUrl: './veiw-sub-destinations.component.html',
  styleUrls: ['./veiw-sub-destinations.component.scss']
})
export class VeiwSubDestinationsComponent implements OnInit {
  destinationRoutes: any;

  constructor(

      public dialogRef: MatDialogRef<VeiwSubDestinationsComponent>,
      @Optional() @Inject(MAT_DIALOG_DATA) public data: any,
      private viewSlotsService: ViewSlotsService
  ) { }

  ngOnInit() {
    this.viewSlotsService.getROutes().then((res) => {
      console.log('TEST UUUU', res.data, this.data.row)
      this.destinationRoutes = res.data.filter((x) => x.ed_bus_routes.ID === this.data.row.ed_bus_route_id)[0];

    })
  }

}
