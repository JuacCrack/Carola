

<?php
require_once '_funciones_ws.php';

/* $xml="<SDTASolicitudes xmlns='AlmangoShop'>
<Nombre>test</Nombre>
<Telefono>01162835031</Telefono>
<Mail>angelcereda@gmail.com</Mail>
<PaisISO></PaisISO>
<DepartamentoId>2</DepartamentoId>
<MunicipioId>15</MunicipioId>
<ZonasID/>
<Direccion>Quintana 1170</Direccion>
<MetodoPagosID>1</MetodoPagosID>
<SolicitudPagada>N</SolicitudPagada>
<SolicitaCotizacion>N</SolicitaCotizacion>
<SolicitaOtroServicio>N</SolicitaOtroServicio>
<OtroServicioDetalle></OtroServicioDetalle>
<FechaInstalacion>2023-07-26</FechaInstalacion>
<TurnoInstalacion>1</TurnoInstalacion>
<Comentario></Comentario>
<ConfirmarCondicionesUso>S</ConfirmarCondicionesUso>
<Level1><Items><RubrosId>1</RubrosId><ProductoID>7</ProductoID><DetalleID>24</DetalleID><Cantidad>1</Cantidad><Precio>1250.00</Precio><SR>R</SR><Comision>0</Comision><ComisionTipo>P</ComisionTipo><PrecioFinal>1250.00</PrecioFinal></Items></Level1>
</SDTASolicitudes>"; */

/* $xml='<?xml version="1.0" encoding="UTF-8"?>'."<SDTASolicitudes xmlns='AlmangoShop'>
<Nombre>test</Nombre>
<Telefono>01162835031</Telefono>
<Mail>angelcereda@gmail.com</Mail>
<PaisISO></PaisISO>
<DepartamentoId>2</DepartamentoId>
<MunicipioId>15</MunicipioId><ZonasID/><Direccion>asdasd 1170 apto: 12 esq: ewrwer</Direccion>
<MetodoPagosID>1</MetodoPagosID>
<SolicitudPagada>N</SolicitudPagada>
<SolicitaCotizacion>N</SolicitaCotizacion><SolicitaOtroServicio>N</SolicitaOtroServicio>
<OtroServicioDetalle></OtroServicioDetalle>
<ProveedorAuxiliar>NO TENGO NI IDEA</ProveedorAuxiliar><FechaInstalacion>2023-07-21</FechaInstalacion>
<TurnoInstalacion>1</TurnoInstalacion>
<Comentario> -  - 2 comentarios</Comentario>
<ConfirmarCondicionesUso>S</ConfirmarCondicionesUso>
<Level1><Items><RubrosId>1</RubrosId><ProductoID>7</ProductoID><DetalleID>22</DetalleID><Cantidad>1</Cantidad><Precio>649.00</Precio><SR>R</SR><Comision>18.03</Comision><ComisionTipo>P</ComisionTipo><PrecioFinal>531.985300</PrecioFinal></Items></Level1></SDTASolicitudes>"; */


/* $xml="<SDTASolicitudes xmlns='AlmangoShop'>
<Nombre>test</Nombre>
<Telefono>01162835031</Telefono>
<Mail>angelcereda@gmail.com</Mail>
<PaisISO></PaisISO>
<DepartamentoId>1</DepartamentoId>
<MunicipioId>1</MunicipioId><ZonasID/>
<ZonasID>9999</ZonasID>
<Direccion>Quintana 123 apto: 1 esq: Tyaus</Direccion>
<MetodoPagosID>1</MetodoPagosID>
<SolicitudPagada>N</SolicitudPagada>
<SolicitaCotizacion>N</SolicitaCotizacion><SolicitaOtroServicio>N</SolicitaOtroServicio>
<OtroServicioDetalle></OtroServicioDetalle>
<ProveedorAuxiliar/><FechaInstalacion>2023-07-27</FechaInstalacion>
<TurnoInstalacion>1</TurnoInstalacion>
<Comentario>1212 - 2 comentarios</Comentario>
<ConfirmarCondicionesUso>S</ConfirmarCondicionesUso>
<Level1><Items><RubrosId>12</RubrosId><ProductoID>1</ProductoID><DetalleID>1</DetalleID><Cantidad>1</Cantidad><Precio>900.00</Precio><SR>R</SR><Comision>0</Comision><ComisionTipo>P</ComisionTipo><PrecioFinal>900.00</PrecioFinal></Items></Level1></SDTASolicitudes>";
 */
/* $xml="<SDTASolicitudes xmlns='AlmangoShop'>
<Nombre>test</Nombre>
<Telefono>01162835031</Telefono>
<Mail>angelcereda@gmail.com</Mail>
<PaisISO></PaisISO>
<DepartamentoId>1</DepartamentoId>
<MunicipioId>1</MunicipioId>
<ZonasID/>
<Direccion>Quintana 123124 apto: 77 esq: ewrwer</Direccion>
<MetodoPagosID>1</MetodoPagosID>
<SolicitudPagada>N</SolicitudPagada>
<SolicitaCotizacion>N</SolicitaCotizacion>
<SolicitaOtroServicio>N</SolicitaOtroServicio>
<OtroServicioDetalle></OtroServicioDetalle>
<ProveedorAuxiliar/>
<FechaInstalacion>2023-07-19</FechaInstalacion>
<TurnoInstalacion>2</TurnoInstalacion>
<Comentario>ret - 2 comentarios</Comentario>
<ConfirmarCondicionesUso>S</ConfirmarCondicionesUso>
<Level1>
    <Items>
        <RubrosId>9</RubrosId>
        <ProductoID>9</ProductoID>
        <DetalleID>12</DetalleID>
        <Cantidad>1</Cantidad>
        <Precio>5663.00</Precio>
        <SR>R</SR>
        <Comision>0</Comision>
        <ComisionTipo>P</ComisionTipo>
        <PrecioFinal>5663.00</PrecioFinal>
    </Items>
</Level1>
</SDTASolicitudes>
";
 */

 $_SESSION['proveedor'] = 1001;
echo '<pre>';
//var_dump(getServicios());
//var_dump(getProductos(7,1001));
//var_dump(getProductosDetalle(3,1001,7));
//var_dump(getProductosDetalle(7,1001,2));
getProductosDetalle(7,1001,2);
//var_dump(findOneServicio(7));
//var_dump(findOneProductoDetalle(7, 21034, 2, 2));
//var_dump(findOneProductoDetalle(3, 21034, 7, 8));
//var_dump(findOneProductoDetalle2(3, 21034, 7, 8));
//var_dump(generarSolicitud($xml));
//var_dump(getsolicitudes());
//var_dump(getDepartamentos('UY'));
//var_dump(getMunicipio('UY',3));
//var_dump(getZonas('UY',1,1));
//var_dump(getTurnos(1001));
//var_dump(getFormasDePago(1001));
//var_dump(getProveedores());
//var_dump(appp());

//generarSolicitud();
echo '</pre>';



  
  