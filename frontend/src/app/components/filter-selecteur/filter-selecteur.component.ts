import { Component, OnInit } from '@angular/core';
import { TasklistService} from '../../services/tasklist.service';

@Component({
  selector: 'app-filter-selecteur',
  templateUrl: './filter-selecteur.component.html',
  styleUrls: ['./filter-selecteur.component.scss']
})
export class FilterSelecteurComponent implements OnInit {
  gewichte: number[];
  selectedGewichtung: string;

  constructor(private tasklistService: TasklistService) {
    this.gewichte = Array(5).fill(0).map((x, i) => i + 1);
  }

  ngOnInit(): void {
  }

  debug() {
    console.log(typeof(this.tasklistService.tasklists$.getValue()));
  }
  filterGewichtung(): void {
    if (this.selectedGewichtung) {
      this.tasklistService.loadTasklistsFiltered('gewichtung', this.selectedGewichtung);
    }
  }
  filterStatus(): void {
    if (this.selectedGewichtung) {
      this.tasklistService.loadTasklistsFiltered('status', this.selectedGewichtung);
    }
  }
  filterZeitpunkt(): void {
    if (this.selectedGewichtung) {
      this.tasklistService.loadTasklistsFiltered('zeitpunkt', this.selectedGewichtung);
    }
  }
  reset(): void {
    this.tasklistService.loadTasklists();
  }
  sortGewichtungAuf(): void {
    this.tasklistService.loadTasklistsSorted('gewichtung', 'sortauf');
  }
  sortGewichtungAb(): void {
    this.tasklistService.loadTasklistsSorted('gewichtung', 'sortab');
  }
  sortStatusAuf(): void {
    this.tasklistService.loadTasklistsSorted('status', 'sortauf');
  }
  sortStatusAb(): void {
    this.tasklistService.loadTasklistsSorted('status', 'sortab');
  }
  sortZeitpunktAuf(): void {
    this.tasklistService.loadTasklistsSorted('zeitpunkt', 'sortauf');
  }
  sortZeitpunktAb(): void {
    this.tasklistService.loadTasklistsSorted('zeitpunkt', 'sortab');
  }
}
