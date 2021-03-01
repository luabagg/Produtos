import { Component, OnInit, ViewChild } from '@angular/core';
import { AlertComponent } from '../alert/alert.component';
import { ActivatedRoute, Router } from '@angular/router';
import { ProductRatingsService } from '../product-ratings.service';
import { ProductRating } from '../product.ratings';

@Component({
  selector: 'app-product-ratings-form',
  templateUrl: './product-ratings-form.component.html',
  styleUrls: ['./product-ratings-form.component.css']
})
export class ProductRatingsFormComponent implements OnInit {

  item: ProductRating;
  errorFields: string[];

  @ViewChild("alert") alert!: any;

  constructor(protected route: ActivatedRoute, protected router: Router, private service: ProductRatingsService) {
    this.item = new ProductRating();
    this.errorFields = [];
  }

  ngOnInit(): void {
    let product_id: string | null = this.route.snapshot.paramMap.get('product_id');
    let id: string | null = this.route.snapshot.paramMap.get('id')

    if (product_id != null) {
      if (id != null) {
        this.service.getOne(parseInt(product_id), parseInt(id)).subscribe(
          (data: any) => this.item = data
        );
      } else {
        this.item.product_id = parseInt(product_id);
      }
    }
  }

  save() {
    if (this.item.id) {
      this.service.update(this.item).subscribe(
        (data: any) => this.callbackSuccess(),
        (error: any) => this.callbackError(error)
      );
    } else {
      this.service.insert(this.item).subscribe(
        (data: any) => this.callbackSuccess(),
        (error: any) => this.callbackError(error)
      );
    }
  }

  isInvalidField(field: string){
    return (this.errorFields.indexOf(field) != -1);
  }

  private callbackSuccess() {
    this.router.navigate(['/produtos/' + this.item.product_id + '/avaliacoes']);
  }

  private callbackError(error: any) {
    Object.keys(error.error).map(field => this.errorFields.push(field));
    this.alert.type = 'danger';

    var message = "Não foi possível salvar";
    switch (error.status) {
      case 422: message = 'Não foi possível salvar. Os campos destacados estão inválidos:'; break;
      case 500: message = 'Algum erro interno ocorreu'; break;
    }
    this.alert.message = message;
  }

}