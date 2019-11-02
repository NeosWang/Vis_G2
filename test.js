let cars=[];
let car={make:"shit1",model:"fuck1",year:2000};
cars.push(car);
cars.push({make:"shit2",model:"fuck2",year:2011});
cars.push({make:"shit3",model:"fuck3",year:1999});
let formatCar = car =>{
    const{make,model,year}=car;
    return`${year} ${make} ${model}`;
};
cars.filter(car=>car.year<2001)
    .map(formatCar)
    .join('\n');
let json=JSON.stringify(cars,null,2);
const newCars=JSON.parse(json);



let myPromise=new Promise( (resolve,reject)=>{
    setTimeout(() => resolve(), 2000);
});
myPromise.then(()=>console.log('Promise respoved'));

let waitSeconds = numSeconds=>new Promise(resolve=>{
    const message=`${numSeconds} second have passed`;
    setTimeout(() => resolve(message), numSeconds*1000);
});

waitSeconds(3)
    .then(message=>console.log(message));