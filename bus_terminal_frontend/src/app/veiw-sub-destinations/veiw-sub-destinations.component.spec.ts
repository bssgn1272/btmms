import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { VeiwSubDestinationsComponent } from './veiw-sub-destinations.component';

describe('VeiwSubDestinationsComponent', () => {
  let component: VeiwSubDestinationsComponent;
  let fixture: ComponentFixture<VeiwSubDestinationsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ VeiwSubDestinationsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(VeiwSubDestinationsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
