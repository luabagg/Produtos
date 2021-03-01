import { Component, OnInit, ViewChild } from '@angular/core';
import { AlertComponent } from '../alert/alert.component';
import { ActivatedRoute, Router } from '@angular/router';
import { Product } from '../products';
import { ProductsService } from '../products.service';

@Component({
  selector: 'app-products-form',
  templateUrl: './products-form.component.html',
  styleUrls: ['./products-form.component.css']
})
export class ProductsFormComponent implements OnInit {

  item: Product;
  errorFields: string[];
  errorMessages: string[];

  @ViewChild("alert") alert!: any;

  constructor(protected route: ActivatedRoute, protected router: Router, private service: ProductsService) {
    this.item = new Product();
    this.item.active = true;
    this.errorFields = [];
    this.errorMessages = [];
  }

  ngOnInit(): void {
    let id: string | null = this.route.snapshot.paramMap.get('id');

    if (id != null) {
      this.service.getOne(parseInt(id)).subscribe((data: any) => this.item = data);
    }
  }

  save() {
    this.errorFields = [];
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
    this.router.navigate(['/produtos']);
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
