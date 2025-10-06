import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminShows } from './admin-shows';

describe('AdminShows', () => {
  let component: AdminShows;
  let fixture: ComponentFixture<AdminShows>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminShows]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AdminShows);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
