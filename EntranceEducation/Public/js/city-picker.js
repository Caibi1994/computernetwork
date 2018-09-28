// jshint ignore: start
+function($){

$.rawCitiesData = [
  {
    "name":"信电学院",
    "code":"110000",
    "sub": [
      {
        "name": "信电17研",
        "code": "110000",
        "sub":[
            {
              "name":"信电17研",
              "code":"110101"
            },
        ]
      },
      {
        "name": "信电16研",
        "code": "",
        "sub":[
            {
              "name":"信电16研",
              "code":""
            },
        ]
      },
    ]
  },
  

  // {"name":"统计学院","code":"","sub":[{"name":"数学1701","code":"","sub":[{"name":"数学1701","code":""}]},{"name":"数学1702","code":"","sub":[{"name":"数学1702","code":""}]},{"name":"数学1703","code":"","sub":[{"name":"数学1703","code":""}]},{"name":"统计1701","code":"","sub":[{"name":"统计1701","code":""}]},{"name":"统计1702","code":"","sub":[{"name":"统计1702","code":""}]},{"name":"统计1703","code":"","sub":[{"name":"统计1703","code":""}]}]},{"name":"财会学院","code":"","sub":[{"name":"会计1701","code":"","sub":[{"name":"会计1701","code":""}]},{"name":"会计1702","code":"","sub":[{"name":"会计1702","code":""}]},{"name":"会计1703","code":"","sub":[{"name":"会计1703","code":""}]},{"name":"会计1704","code":"","sub":[{"name":"会计1704","code":""}]},{"name":"会计1705","code":"","sub":[{"name":"会计1705","code":""}]},{"name":"会计1706","code":"","sub":[{"name":"会计1706","code":""}]}]},{"name":"经济学院","code":"","sub":[{"name":"经济1701","code":"","sub":[{"name":"经济1701","code":""}]},{"name":"经济1702","code":"","sub":[{"name":"经济1702","code":""}]},{"name":"经济1703","code":"","sub":[{"name":"经济1703","code":""}]},{"name":"经济1704","code":"","sub":[{"name":"经济1704","code":""}]},{"name":"经济1705","code":"","sub":[{"name":"经济1705","code":""}]},{"name":"经济1706","code":"","sub":[{"name":"经济1706","code":""}]},{"name":"经济1707","code":"","sub":[{"name":"经济1707","code":""}]}]},{"name":"旅游学院","code":"","sub":[{"name":"旅游(专升本)1701","code":"","sub":[{"name":"旅游(专升本)1701","code":""}]},{"name":"旅游(专升本)1702","code":"","sub":[{"name":"旅游(专升本)1702","code":""}]},{"name":"城规1701","code":"","sub":[{"name":"城规1701","code":""}]},{"name":"城规1702","code":"","sub":[{"name":"城规1702","code":""}]},{"name":"旅游1701","code":"","sub":[{"name":"旅游1701","code":""}]},{"name":"旅游1702","code":"","sub":[{"name":"旅游1702","code":""}]},{"name":"旅游1703","code":"","sub":[{"name":"旅游1703","code":""}]}]},{"name":"金融学院","code":"","sub":[{"name":"保险1701","code":"","sub":[{"name":"保险1701","code":""}]},{"name":"金融1701","code":"","sub":[{"name":"金融1701","code":""}]},{"name":"金融1702","code":"","sub":[{"name":"金融1702","code":""}]},{"name":"金融1703","code":"","sub":[{"name":"金融1703","code":""}]},{"name":"金融1704","code":"","sub":[{"name":"金融1704","code":""}]},{"name":"金融1705","code":"","sub":[{"name":"金融1705","code":""}]}]},{"name":"外国语学院","code":"","sub":[{"name":"法语1701","code":"","sub":[{"name":"法语1701","code":""}]},{"name":"英语1701","code":"","sub":[{"name":"英语1701","code":""}]},{"name":"英语1702","code":"","sub":[{"name":"英语1702","code":""}]},{"name":"英语1703","code":"","sub":[{"name":"英语1703","code":""}]},{"name":"英语1704","code":"","sub":[{"name":"英语1704","code":""}]},{"name":"英语1705","code":"","sub":[{"name":"英语1705","code":""}]},{"name":"英语1706","code":"","sub":[{"name":"英语1706","code":""}]}]},{"name":"艺术学院","code":"","sub":[{"name":"美术1701","code":"","sub":[{"name":"美术1701","code":""}]},{"name":"动画1701","code":"","sub":[{"name":"动画1701","code":""}]},{"name":"动画1702","code":"","sub":[{"name":"动画1702","code":""}]},{"name":"视觉1701","code":"","sub":[{"name":"视觉1701","code":""}]},{"name":"视觉1702","code":"","sub":[{"name":"视觉1702","code":""}]},{"name":"视觉1703","code":"","sub":[{"name":"视觉1703","code":""}]},{"name":"环境1701","code":"","sub":[{"name":"环境1701","code":""}]},{"name":"环境1702","code":"","sub":[{"name":"环境1702","code":""}]},{"name":"产品1701","code":"","sub":[{"name":"产品1701","code":""}]},{"name":"环境(专升本)1701","code":"","sub":[{"name":"环境(专升本)1701","code":""}]}]},{"name":"法学院","code":"","sub":[{"name":"法学1701","code":"","sub":[{"name":"法学1701","code":""}]},{"name":"法学1702","code":"","sub":[{"name":"法学1702","code":""}]},{"name":"法学1703","code":"","sub":[{"name":"法学1703","code":""}]},{"name":"法学1704","code":"","sub":[{"name":"法学1704","code":""}]},{"name":"法学1705","code":"","sub":[{"name":"法学1705","code":""}]}]},{"name":"食品学院","code":"","sub":[{"name":"生物1701","code":"","sub":[{"name":"生物1701","code":""}]},{"name":"化学1701","code":"","sub":[{"name":"化学1701","code":""}]},{"name":"化学1702","code":"","sub":[{"name":"化学1702","code":""}]},{"name":"食品1701","code":"","sub":[{"name":"食品1701","code":""}]},{"name":"食品1702","code":"","sub":[{"name":"食品1702","code":""}]},{"name":"食品1703","code":"","sub":[{"name":"食品1703","code":""}]},{"name":"食品1704","code":"","sub":[{"name":"食品1704","code":""}]},{"name":"食品1705","code":"","sub":[{"name":"食品1705","code":""}]}]},{"name":"信电学院","code":"","sub":[{"name":"电子1701","code":"","sub":[{"name":"电子1701","code":""}]},{"name":"电子1702","code":"","sub":[{"name":"电子1702","code":""}]},{"name":"电子1703","code":"","sub":[{"name":"电子1703","code":""}]},{"name":"电子1704","code":"","sub":[{"name":"电子1704","code":""}]},{"name":"网络1701","code":"","sub":[{"name":"网络1701","code":""}]},{"name":"网络1702","code":"","sub":[{"name":"网络1702","code":""}]},{"name":"网络1703","code":"","sub":[{"name":"网络1703","code":""}]}]},{"name":"信息学院","code":"","sub":[{"name":"计算机1701","code":"","sub":[{"name":"计算机1701","code":""}]},{"name":"计算机1702","code":"","sub":[{"name":"计算机1702","code":""}]},{"name":"计算机1703","code":"","sub":[{"name":"计算机1703","code":""}]},{"name":"计算机1704","code":"","sub":[{"name":"计算机1704","code":""}]},{"name":"计算机1705","code":"","sub":[{"name":"计算机1705","code":""}]}]},{"name":"人文学院","code":"","sub":[{"name":"汉语1701","code":"","sub":[{"name":"汉语1701","code":""}]},{"name":"历史1701","code":"","sub":[{"name":"历史1701","code":""}]},{"name":"汉语(专升本)1701","code":"","sub":[{"name":"汉语(专升本)1701","code":""}]},{"name":"传播1701","code":"","sub":[{"name":"传播1701","code":""}]},{"name":"传播1702","code":"","sub":[{"name":"传播1702","code":""}]},{"name":"传播1703","code":"","sub":[{"name":"传播1703","code":""}]}]},{"name":"公管学院","code":"","sub":[{"name":"社会1701","code":"","sub":[{"name":"社会1701","code":""}]},{"name":"公管1701","code":"","sub":[{"name":"公管1701","code":""}]},{"name":"公管1702","code":"","sub":[{"name":"公管1702","code":""}]},{"name":"公管1703","code":"","sub":[{"name":"公管1703","code":""}]},{"name":"公管1704","code":"","sub":[{"name":"公管1704","code":""}]},{"name":"公管1705","code":"","sub":[{"name":"公管1705","code":""}]},{"name":"公管1706","code":"","sub":[{"name":"公管1706","code":""}]}]},{"name":"东语学院","code":"","sub":[{"name":"日语1701","code":"","sub":[{"name":"日语1701","code":""}]},{"name":"日语1702","code":"","sub":[{"name":"日语1702","code":""}]},{"name":"阿拉伯语1701","code":"","sub":[{"name":"阿拉伯语1701","code":""}]},{"name":"日语(专升本)1701","code":"","sub":[{"name":"日语(专升本)1701","code":""}]}]},{"name":"环境学院","code":"","sub":[{"name":"环境1701","code":"","sub":[{"name":"环境1701","code":""}]},{"name":"环境1702","code":"","sub":[{"name":"环境1702","code":""}]},{"name":"环境1703","code":"","sub":[{"name":"环境1703","code":""}]},{"name":"环境1704","code":"","sub":[{"name":"环境1704","code":""}]},{"name":"环境1705","code":"","sub":[{"name":"环境1705","code":""}]},{"name":"环境1706","code":"","sub":[{"name":"环境1706","code":""}]}]},{"name":"马克思学院","code":"","sub":[{"name":"哲学1701","code":"","sub":[{"name":"哲学1701","code":""}]}]},{"name":"管工学院","code":"","sub":[{"name":"工管1701","code":"","sub":[{"name":"工管1701","code":""}]},{"name":"工管1702","code":"","sub":[{"name":"工管1702","code":""}]},{"name":"电商1701","code":"","sub":[{"name":"电商1701","code":""}]},{"name":"电商1702","code":"","sub":[{"name":"电商1702","code":""}]},{"name":"电商1703","code":"","sub":[{"name":"电商1703","code":""}]},{"name":"电商1704","code":"","sub":[{"name":"电商1704","code":""}]},{"name":"电商1705","code":"","sub":[{"name":"电商1705","code":""}]},{"name":"电商1706","code":"","sub":[{"name":"电商1706","code":""}]}]}

 
];
}($);
// jshint ignore: end

/* global $:true */
/* jshint unused:false*/

+ function($) {
  "use strict";

  var defaults;
  var raw = $.rawCitiesData;

  var format = function(data) {
    var result = [];
    for(var i=0;i<data.length;i++) {
      var d = data[i];
      if(/^请选择|市辖区/.test(d.name)) continue;
      result.push(d);
    }
    if(result.length) return result;
    return [];
  };

  var sub = function(data) {
    if(!data.sub) return [{ name: '', code: data.code }];  // 有可能某些县级市没有区
    return format(data.sub);
  };

  var getCities = function(d) {
    for(var i=0;i< raw.length;i++) {
      if(raw[i].code === d || raw[i].name === d) return sub(raw[i]);
    }
    return [];
  };

  var getDistricts = function(p, c) {
    for(var i=0;i< raw.length;i++) {
      if(raw[i].code === p || raw[i].name === p) {
        for(var j=0;j< raw[i].sub.length;j++) {
          if(raw[i].sub[j].code === c || raw[i].sub[j].name === c) {
            return sub(raw[i].sub[j]);
          }
        }
      }
    }
  };

  var parseInitValue = function (val) {
    var p = raw[0], c, d;
    var tokens = val.split(' ');
    raw.map(function (t) {
      if (t.name === tokens[0]) p = t;
    });

    p.sub.map(function (t) {
      if (t.name === tokens[1]) c = t;
    })

    if (tokens[2]) {
      c.sub.map(function (t) {
        if (t.name === tokens[2]) d = t;
      })
    }

    if (d) return [p.code, c.code, d.code];
    return [p.code, c.code];
  }

  $.fn.cityPicker = function(params) {
    params = $.extend({}, defaults, params);
    return this.each(function() {
      var self = this;
      
      var provincesName = raw.map(function(d) {
        return d.name;
      });
      var provincesCode = raw.map(function(d) {
        return d.code;
      });
      var initCities = sub(raw[0]);
      var initCitiesName = initCities.map(function (c) {
        return c.name;
      });
      var initCitiesCode = initCities.map(function (c) {
        return c.code;
      });
      var initDistricts = sub(raw[0].sub[0]);

      var initDistrictsName = initDistricts.map(function (c) {
        return c.name;
      });
      var initDistrictsCode = initDistricts.map(function (c) {
        return c.code;
      });

      var currentProvince = provincesName[0];
      var currentCity = initCitiesName[0];
      var currentDistrict = initDistrictsName[0];

      var cols = [
          {
            displayValues: provincesName,
            values: provincesCode,
            cssClass: "col-province"
          },
          {
            displayValues: initCitiesName,
            values: initCitiesCode,
            cssClass: "col-city"
          }
        ];

        if(params.showDistrict) cols.push({
          values: initDistrictsCode,
          displayValues: initDistrictsName,
          cssClass: "col-district"
        });

      var config = {

        cssClass: "city-picker",
        rotateEffect: false,  //为了性能
        formatValue: function (p, values, displayValues) {
          return displayValues.join(' ');
        },
        onChange: function (picker, values, displayValues) {
          var newProvince = picker.cols[0].displayValue;
          var newCity;
          if(newProvince !== currentProvince) {
            var newCities = getCities(newProvince);
            newCity = newCities[0].name;
            var newDistricts = getDistricts(newProvince, newCity);
            picker.cols[1].replaceValues(newCities.map(function (c) {
              return c.code;
            }), newCities.map(function (c) {
              return c.name;
            }));
            if(params.showDistrict) picker.cols[2].replaceValues(newDistricts.map(function (d) {
              return d.code;
            }), newDistricts.map(function (d) {
              return d.name;
            }));
            currentProvince = newProvince;
            currentCity = newCity;
            picker.updateValue();
            return false; // 因为数据未更新完，所以这里不进行后序的值的处理
          } else {
            if(params.showDistrict) {
              newCity = picker.cols[1].displayValue;
              if(newCity !== currentCity) {
                var districts = getDistricts(newProvince, newCity);
                picker.cols[2].replaceValues(districts.map(function (d) {
                  return d.code;
                }), districts.map(function (d) {
                  return d.name;
                }));
                currentCity = newCity;
                picker.updateValue();
                return false; // 因为数据未更新完，所以这里不进行后序的值的处理
              }
            }
          }
          //如果最后一列是空的，那么取倒数第二列
          var len = (values[values.length-1] ? values.length - 1 : values.length - 2)
          $(self).attr('data-code', values[len]);
          $(self).attr('data-codes', values.join(','));
          if (params.onChange) {
            params.onChange.call(self, picker, values, displayValues);
          }
        },

        cols: cols
      };

      if(!this) return;
      var p = $.extend({}, params, config);
      //计算value
      var val = $(this).val();
      if (!val) val = '北京 信电17研 东城区';
      currentProvince = val.split(" ")[0];
      currentCity = val.split(" ")[1];
      currentDistrict= val.split(" ")[2];
      if(val) {
        p.value = parseInitValue(val);
        if(p.value[0]) {
          var cities = getCities(p.value[0]);
          p.cols[1].values = cities.map(function (c) {
            return c.code;
          });
          p.cols[1].displayValues = cities.map(function (c) {
            return c.name;
          });
        }

        if(p.value[1]) {
          if (params.showDistrict) {
            var dis = getDistricts(p.value[0], p.value[1]);
            p.cols[2].values = dis.map(function (d) {
              return d.code;
            });
            p.cols[2].displayValues = dis.map(function (d) {
              return d.name;
            });
          }
        } else {
          if (params.showDistrict) {
            var dis = getDistricts(p.value[0], p.cols[1].values[0]);
            p.cols[2].values = dis.map(function (d) {
              return d.code;
            });
            p.cols[2].displayValues = dis.map(function (d) {
              return d.name;
            });
          }
        }
      }
      $(this).picker(p);
    });
  };

  defaults = $.fn.cityPicker.prototype.defaults = {
    showDistrict: true //是否显示地区选择
  };

}($);
