# Hyperf Admin


- hyperf 2.2


# 不要使用依赖注入方式调用数据模型
```
 不要用依赖注入的方式来调用 User 的model 对象,
  因为依赖注入的是一个长生命周期的对象, 
  也就是说如果这样干了, User的这个对象就是在内存中常驻的了, 
  可能你第一次查询的时候结果是正确的, 
  当第二个,第三个请求来查询时, 
  其是得到的是一个被污染的对象
```