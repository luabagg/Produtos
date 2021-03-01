import { Component, OnInit, ViewChild } from '@angular/core';
import { AlertComponent } from '../alert/alert.component';
import { Product } from '../products';
import { ProductsService } from '../products.service';

@Component({
  selector: 'app-products-list',
  templateUrl: './products-list.component.html',
  styleUrls: ['./products-list.component.css']
})
export class ProductsListComponent implements OnInit {

  items!: Product[];

  @ViewChild("alert") alert!: any;

  constructor(private service: ProductsService) {

  }

  ngOnInit(): void {
    this.getAll();
  }

  getAll() {
    this.service.getAll().subscribe((data: any) => this.items = data);
  }

  delete(id: number) {
    this.service.delete(id).subscribe(
      (data: any) => this.callbackSuccess(data.message),
      (error: any) => this.callbackError(error)
    );
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