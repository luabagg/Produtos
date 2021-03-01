import { Component, OnInit, ViewChild } from '@angular/core';
import { AlertComponent } from '../alert/alert.component';
import { ActivatedRoute } from '@angular/router';
import { ProductRatingsService } from '../product-ratings.service';
import { ProductRating } from '../product.ratings';

@Component({
  selector: 'app-product-ratings-list',
  templateUrl: './product-ratings-list.component.html',
  styleUrls: ['./product-ratings-list.component.css']
})
export class ProductRatingsListComponent implements OnInit {
  items!: ProductRating[];
  product_id: string | null;
  
  @ViewChild("alert") alert!: any;

  constructor(protected route: ActivatedRoute, private service: ProductRatingsService) {
    this.product_id = null;
  }

  ngOnInit(): void {
    this.product_id = this.route.snapshot.paramMap.get('product_id');

    this.getAll();
  }

  getAll() {
    if (this.product_id != null) {
      this.service.getAll(parseInt(this.product_id)).subscribe((data: any) => this.items = data)
    }
  }

  delete(id: number) {
    if (this.product_id) {
      this.service.delete(parseInt(this.product_id), id).subscribe(
        (data: any) => this.callbackSuccess(data.message),
        (error: any) => this.callbackError(error)
      );
    }

  }

  private callbackSuccess(message: string) {
    this.getAll();
    this.alert.message = message;
  }

  private callbackError(error: any) {
    this.alert.type = 'danger';
    this.alert.message = 'Ocorreu um problema ao excluir';
  }

}