import { Component, inject, OnInit } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { Show, ShowService } from '../../services/show';
import { AuthService } from '../../services/auth';

@Component({
  selector: 'app-shows',
  standalone: true,
  imports: [RouterLink],
  templateUrl: './shows.html',
  styleUrls: ['./shows.css']
})
export class ShowsComponent implements OnInit {
  showService = inject(ShowService);
  authService = inject(AuthService);
  router = inject(Router);

  shows: Show[] = [];

  ngOnInit(): void {
    this.showService.getShows().subscribe(data => {
      this.shows = data;
    });
  }

}
