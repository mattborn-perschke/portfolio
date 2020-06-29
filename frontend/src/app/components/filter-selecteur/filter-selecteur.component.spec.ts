import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FilterSelecteurComponent } from './filter-selecteur.component';

describe('FilterSelecteurComponent', () => {
  let component: FilterSelecteurComponent;
  let fixture: ComponentFixture<FilterSelecteurComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FilterSelecteurComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FilterSelecteurComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
