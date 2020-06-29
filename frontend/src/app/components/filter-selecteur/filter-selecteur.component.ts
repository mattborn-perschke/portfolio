import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-filter-selecteur',
  templateUrl: './filter-selecteur.component.html',
  styleUrls: ['./filter-selecteur.component.scss']
})
export class FilterSelecteurComponent implements OnInit {

  selectedValue: string;
  constructor() { }

  ngOnInit(): void {
  }

}
