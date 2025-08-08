@extends('client.layouts.app')

@section('title', 'Trang Chủ')

@section('content')
    <main id="main" class="site-main">
        <div class="container" bis_skin_checked="1">
            <div class="order-block__title justify-content-center pt-4 pb-2" bis_skin_checked="1">
                <h3 class="text-uppercase">Đăng ký</h3>
            </div>
            <div class="auth auth-forgotpass" bis_skin_checked="1">
                <div class="row" style="display: block" bis_skin_checked="1">
                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf
                        <div class="col-md-6 col-sm-6 col-xs-12" bis_skin_checked="1">
                            <div class="register-summary__overview" bis_skin_checked="1">
                                <h4>Thông tin khách hàng</h4>
                            </div>
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-warning alert-dismissible" role="alert"
                                    style="background-color: #f3e8e9; color: red; font-size: 14px; line-height: 24px">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    @foreach ($errors->all() as $error)
                                        <strong>Lỗi! </strong>{{ $error }}<br>
                                    @endforeach
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-warning alert-dismissible" role="alert"
                                    style="background-color: #f3e8e9; color: red; font-size: 14px; line-height: 24px">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <strong>Lỗi! </strong>{{ session('error') }}<br>
                                </div>
                            @endif
                            <div class="row" bis_skin_checked="1">
                                {{-- <div class="col-md-6 col-sm-6 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Họ:<span style="color: red">*</span></label>
                                        <input type="text" class="form-control" value="" name="customer_firstname"
                                            placeholder="Họ..." style="width: 100%" fdprocessedid="qkqyes">
                                    </div>
                                </div> --}}
                                <div class="col-md-6 col-sm-6 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Tên:<span style="color: red">*</span></label>
                                        <input class="form-control" type="text" name="name" placeholder="Tên..."
                                            value="{{ old('name') }}" style="width: 100%" fdprocessedid="3oa49">
                                    </div>
                                </div>
                            </div>
                            <div class="row" bis_skin_checked="1">
                                <div class="col-md-6 col-sm-6 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Email:<span style="color: red">*</span></label>
                                        <input id="email" class="form-control" type="email" name="email"
                                            value="{{ old('email') }}" placeholder="Email..." style="width: 100%"
                                            fdprocessedid="1l3u1">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Điện thoại:<span style="color: red">*</span></label>
                                        <input class="form-control" type="text" value="{{ old('phone') }}"
                                            name="phone" placeholder="Điện thoại..." style="width: 100%"
                                            fdprocessedid="op89mj">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row" bis_skin_checked="1">
                                <div class="col-md-6 col-sm-6 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Ngày sinh:<span style="color: red">*</span></label>
                                        <input type="text" class="form-control datepicker hasDatepicker"
                                            name="customer_birthday" value="" placeholder="Ngày sinh..."
                                            style="width: 100%" id="dp1752643211404" fdprocessedid="947n7">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Giới tính:<span style="color: red">*</span></label>
                                        <select name="customer_sex" style="width: 100%" class="form-control"
                                            fdprocessedid="im46xd">
                                            <option value="0">Nữ</option>
                                            <option value="1">Nam</option>
                                            <option value="2">Khác</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" bis_skin_checked="1">
                                <div class="col-md-6 col-sm-6 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Tỉnh/TP:<span style="color: red">*</span></label>
                                        <select class="form-control" name="register_region_id" id="register_region_id"
                                            style="width: 100%" fdprocessedid="ofias">
                                            <option value="-1">Chọn Tỉnh/Tp</option>
                                            <option value="511">Hà Nội</option>
                                            <option value="507">Hồ Chí Minh</option>
                                            <option value="512">Hải Phòng</option>
                                            <option value="499">Đà Nẵng</option>
                                            <option value="485">An Giang</option>
                                            <option value="486">Bình Dương</option>
                                            <option value="487">Bắc Giang</option>
                                            <option value="488">Bình Định</option>
                                            <option value="490">Bạc Liêu</option>
                                            <option value="491">Bắc Ninh</option>
                                            <option value="492">Bình Phước</option>
                                            <option value="494">Bình Thuận</option>
                                            <option value="495">Bến Tre</option>
                                            <option value="496">Cao Bằng</option>
                                            <option value="497">Cà Mau</option>
                                            <option value="498">Cần Thơ</option>
                                            <option value="500">Điện Biên</option>
                                            <option value="502">Đồng Nai</option>
                                            <option value="504">Đồng Tháp</option>
                                            <option value="505">Gia Lai</option>
                                            <option value="506">Hòa Bình</option>
                                            <option value="508">Hải Dương</option>
                                            <option value="509">Hà Giang</option>
                                            <option value="510">Hà Nam</option>
                                            <option value="513">Hà Tĩnh</option>
                                            <option value="514">Hậu Giang</option>
                                            <option value="515">Hưng Yên</option>
                                            <option value="516">Kiên Giang</option>
                                            <option value="517">Khánh Hòa</option>
                                            <option value="518">Kon Tum</option>
                                            <option value="519">Long An</option>
                                            <option value="520">Lâm Đồng</option>
                                            <option value="521">Lai Châu</option>
                                            <option value="522">Lào Cai</option>
                                            <option value="523">Lạng Sơn</option>
                                            <option value="524">Nghệ An</option>
                                            <option value="525">Ninh Bình</option>
                                            <option value="526">Nam Định</option>
                                            <option value="527">Ninh Thuận</option>
                                            <option value="528">Phú Thọ</option>
                                            <option value="529">Phú Yên</option>
                                            <option value="530">Quảng Bình</option>
                                            <option value="531">Quảng Ngãi</option>
                                            <option value="532">Quảng Nam</option>
                                            <option value="533">Quảng Ninh</option>
                                            <option value="534">Quảng Trị</option>
                                            <option value="535">Sơn La</option>
                                            <option value="536">Sóc Trăng</option>
                                            <option value="537">Thái Bình</option>
                                            <option value="538">Tiền Giang</option>
                                            <option value="539">Thanh Hóa</option>
                                            <option value="540">Tây Ninh</option>
                                            <option value="541">Tuyên Quang</option>
                                            <option value="543">Trà Vinh</option>
                                            <option value="544">Thái Nguyên</option>
                                            <option value="545">Vĩnh Long</option>
                                            <option value="546">Vĩnh Phúc</option>
                                            <option value="547">Yên Bái</option>
                                            <option value="548">Đắk Nông</option>
                                            <option value="549">Bắc Kạn</option>
                                            <option value="550">Thừa Thiên - Huế</option>
                                            <option value="551">Đắk Lắk</option>
                                            <option value="552">Bà Rịa - Vũng Tàu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Quận/Huyện:<span style="color: red">*</span></label>
                                        <select name="register_city_id" id="register_city_id" style="width: 100%"
                                            class="form-control" fdprocessedid="hglsad">
                                            <option value="-1">Chọn Quận/Huyện</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" bis_skin_checked="1">
                                <div class="col-md-12 col-sm-12 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Phường/Xã:<span style="color: red">*</span></label>
                                        <select name="vnward_id" id="vnward_id" style="width: 100%" class="form-control"
                                            fdprocessedid="ctl6ci">
                                            <option value="-1">Chọn Phường/Xã</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row" bis_skin_checked="1">
                                <div class="col-md-12 col-sm-12 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Địa chỉ:<span style="color: red">*</span></label>
                                        <textarea class="form-control" name="address">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12" bis_skin_checked="1">
                            <div class="register-summary__overview" bis_skin_checked="1">
                                <h4>Thông tin mật khẩu</h4>
                            </div>
                            <div class="row" bis_skin_checked="1">
                                <div class="col-md-12 col-sm-12 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Mật khẩu:<span style="color: red">*</span></label>
                                        <input class="form-control" type="password" value="" name="password"
                                            placeholder="Mật khẩu..." fdprocessedid="pulloj">
                                    </div>
                                </div>
                            </div>
                            <div class="row" bis_skin_checked="1">
                                <div class="col-md-12 col-sm-12 col-xs-12" bis_skin_checked="1">
                                    <div class="form-group" bis_skin_checked="1">
                                        <label>Nhập lại mật khẩu:<span style="color: red">*</span></label>
                                        <input class="form-control" type="password" value=""
                                            name="password_confirmation" placeholder="Nhập lại mật khẩu..."
                                            fdprocessedid="735ywu">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" bis_skin_checked="1">
                                <label for="captcha" class="form-label">Mã xác thực</label>
                                <div style="display:flex; align-items:center;">
                                    <input class="form-control" name="captcha" type="text" placeholder="Nhập mã"
                                        value="{{ old('captcha') }}" style="max-width:130px; margin-right:10px;">
                                    <img id="captcha-img" src="{{ captcha_src('flat') }}"
                                        style="height:38px; border-radius:6px; border:1px solid #ddd;">
                                    <button type="button" id="reload-captcha" class="btn btn-light"
                                        style="margin-left:10px; height:38px; border-radius:6px; border:1px solid #ddd;">&#8635;</button>
                                </div>
                                @if ($errors->has('captcha'))
                                    <span style="color:red">{{ $errors->first('captcha') }}</span>
                                @endif
                            </div>

                            @push('scripts')
                                <script>
                                    document.getElementById('reload-captcha').onclick = function() {
                                        fetch("{{ route('reload.captcha') }}")
                                            .then(res => res.json())
                                            .then(data => {
                                                document.getElementById('captcha-img').innerHTML = data.captcha;
                                            });
                                    };
                                </script>
                            @endpush
                            <div class="row" bis_skin_checked="1">
                                <div class="col-md-12 col-sm-12 col-xs-12" bis_skin_checked="1">
                                    <div class="form-check" bis_skin_checked="1">
                                        <input class="form-check-input checkboxs" type="checkbox" name="customer_agree"
                                            value="1" id="defaultCheck1">
                                        <label style="margin-top: 4px;margin-left: 3px;" class="form-check-label"
                                            for="defaultCheck1">
                                            <span> Đồng ý với các <a style="color: #f31f1f"
                                                    href="https://ivymoda.com/about/chinh-sach-bao-hanh">điều khoản</a> của
                                                IVY </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12" bis_skin_checked="1">
                                    <div class="form-check" bis_skin_checked="1">
                                        <input class=" form-check-input checkboxs" type="checkbox" value="1"
                                            name="customer_subscribe" id="defaultCheck2">
                                        <label style="margin-top: 4px;margin-left: 3px;" class="form-check-label"
                                            for="defaultCheck2">
                                            <span>Đăng ký nhận bản tin</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" bis_skin_checked="1">
                                <div class="col-md-12 col-sm-12 col-xs-12" bis_skin_checked="1">
                                    <button class="btn btn--large" type="submit" style="width: 100%;margin-top: 15px"
                                        fdprocessedid="0z2uoa">Đăng ký</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var reloadBtn = document.getElementById('reload-captcha');
            if (reloadBtn) {
                reloadBtn.onclick = function() {
                    // Đổi mã bằng cách đổi src ảnh (cache buster)
                    document.getElementById('captcha-img').src = '{{ captcha_src('flat') }}' + '?' + Math
                        .random();
                };
            }
        });
    </script>
@endsection
